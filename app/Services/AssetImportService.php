<?php

namespace App\Services;

use Exception;
use Generator;
use Illuminate\Support\Facades\Log;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Reader\CSV\Reader as CsvReader;
use OpenSpout\Reader\CSV\Options as CsvOptions;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;
use OpenSpout\Reader\XLSX\Options as XlsxOptions;

class AssetImportService
{
    /**
     * Bilingual keyword map: column header keywords → canonical field names.
     * Each entry uses regex with prefix/suffix tolerance.
     */
    private const HEADER_MAP = [
        'tag'           => '/(tag|kode\s*aset|asset\s*tag|kode)/i',
        'name'          => '/(nama|name|nama\s*aset|asset\s*name|deskripsi|description)/i',
        'category'      => '/(kategori|category|jenis|type|tipe)/i',
        'department'    => '/(departemen|department|dept|bagian|divisi|division)/i',
        'status'        => '/(status|kondisi|condition)/i',
        'serial_number' => '/(serial|seri|serial\s*number|no\s*seri|nomor\s*seri|s\/?n)/i',
        'purchase_date' => '/(tanggal\s*beli|purchase\s*date|tgl\s*beli|date|tanggal)/i',
        'brand'         => '/(merk|merek|brand|pabrikan|manufacturer)/i',
        'model'         => '/(model|tipe|type)/i',
        'vendor'        => '/(vendor|supplier|pemasok)/i',
        'cost'          => '/(harga|cost|price|biaya|purchase\s*cost|nilai)/i',
    ];

    /**
     * Parse an uploaded file using stream-based row-by-row reading.
     * Returns a flat array of mapped asset rows.
     *
     * @param  string  $filePath  Absolute path to the uploaded temp file
     * @param  string  $extension  File extension (csv or xlsx)
     * @return array The parsed array of asset rows
     *
     * @throws Exception If header detection fails or file is unreadable
     */
    public function parseFile(string $filePath, string $extension): array
    {
        $results = [];
        $headerMap = null;
        $headerRowIndex = null;
        $scannedRows = 0;
        $maxHeaderScanRows = 15; // Only scan top 15 rows for header

        foreach ($this->readRows($filePath, $extension) as $rowIndex => $cells) {
            $scannedRows++;

            // Phase 1: Header detection (scan first N rows)
            if ($headerMap === null && $scannedRows <= $maxHeaderScanRows) {
                $detected = $this->detectHeader($cells);
                if ($detected !== null) {
                    $headerMap = $detected;
                    $headerRowIndex = $rowIndex;
                    Log::info("Header detected at row {$rowIndex}: " . json_encode($headerMap));
                    continue;
                }
                continue; // Skip pre-header rows
            }

            // If we scanned N rows and found no header, abort
            if ($headerMap === null && $scannedRows > $maxHeaderScanRows) {
                throw new Exception(__('assets.import_parse_error', [
                    'message' => 'Could not detect a valid header row in the first 15 rows.',
                ]));
            }

            // Skip the header row itself
            if ($rowIndex === $headerRowIndex) {
                continue;
            }

            // Phase 2: Data extraction
            $mapped = $this->mapRow($cells, $headerMap);

            // Skip completely empty rows
            if ($this->isEmptyRow($mapped)) {
                continue;
            }

            $results[] = $mapped;
        }

        // Edge case: file had a header but zero data rows
        if ($headerMap === null) {
            Log::warning('Import file had no detectable header row.');
        }

        return $results;
    }

    /**
     * Generator: yields rows one at a time from CSV or XLSX.
     * Memory-efficient — never loads entire file.
     */
    private function readRows(string $filePath, string $extension): Generator
    {
        if ($extension === 'csv') {
            $options = new CsvOptions();
            $reader = new CsvReader($options);
        } else {
            $options = new XlsxOptions();
            $reader = new XlsxReader($options);
        }

        $reader->open($filePath);

        $rowIndex = 0;
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                /** @var Row $row */
                $cells = array_map(function ($val) {
                    return is_string($val) ? trim($val) : $val;
                }, $row->toArray());

                yield $rowIndex => $cells;
                $rowIndex++;
            }
            break; // Only process the first sheet
        }

        $reader->close();
    }

    /**
     * Heuristic header detection.
     * A row is considered a header if it matches >= 2 known column keywords.
     *
     * @return array|null  Map of canonical_field => column_index, or null if not a header
     */
    private function detectHeader(array $cells): ?array
    {
        $map = [];
        $matchCount = 0;

        foreach ($cells as $colIndex => $cellValue) {
            if (empty($cellValue) || !is_string($cellValue)) {
                continue;
            }

            $normalized = strtolower(trim($cellValue));

            foreach (self::HEADER_MAP as $field => $pattern) {
                if (isset($map[$field])) {
                    continue; // Already mapped this field
                }

                if (preg_match($pattern, $normalized)) {
                    $map[$field] = $colIndex;
                    $matchCount++;
                    break; // One cell → one field
                }
            }
        }

        // Require at least 2 keyword matches to confirm this is a header row
        return $matchCount >= 2 ? $map : null;
    }

    /**
     * Map a data row's cells into a standardized asset array using the header map.
     */
    private function mapRow(array $cells, array $headerMap): array
    {
        $get = function (string $field) use ($cells, $headerMap) {
            if (!isset($headerMap[$field])) {
                return '';
            }
            $val = $cells[$headerMap[$field]] ?? '';
            // Handle DateTimeInterface objects from XLSX
            if ($val instanceof \DateTimeInterface) {
                return $val->format('Y-m-d');
            }
            return is_string($val) ? trim($val) : (string) $val;
        };

        // Combine brand + model into a single field for the review UI
        $brand = $get('brand');
        $model = $get('model');
        $combined = trim("{$brand} {$model}");

        return [
            'tag'           => $get('tag'),
            'name'          => $get('name'),
            'category_id'   => '', // Will be mapped by user on review page
            'department_id' => '', // Will be mapped by user on review page
            'status'        => $this->normalizeStatus($get('status')),
            'model'         => $combined,
            'serial_number' => $get('serial_number'),
            'purchase_date' => $get('purchase_date'),
            '_category_hint' => $get('category'),
            '_department_hint' => $get('department'),
        ];
    }

    /**
     * Normalize status strings to valid enum values.
     */
    private function normalizeStatus(string $raw): string
    {
        $raw = strtolower(trim($raw));

        if (preg_match('/(aktif|active|in.?service|baik|good|bagus)/i', $raw)) {
            return 'in_service';
        }
        if (preg_match('/(rusak|broken|out.?of.?service|tidak.?aktif|inactive|non.?aktif)/i', $raw)) {
            return 'out_of_service';
        }
        if (preg_match('/(disposed|dibuang|dihapus|removed|scrap)/i', $raw)) {
            return 'disposed';
        }

        return 'in_service'; // Default
    }

    /**
     * Check if a mapped row is completely empty (no meaningful data).
     */
    private function isEmptyRow(array $row): bool
    {
        $checkFields = ['tag', 'name', 'serial_number', 'model', 'purchase_date'];
        foreach ($checkFields as $field) {
            if (!empty($row[$field])) {
                return false;
            }
        }
        return true;
    }
}
