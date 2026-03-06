<?php

namespace App\Http\Controllers;

use App\Services\AssetImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AssetImportController extends Controller
{
    private AssetImportService $importService;

    public function __construct(AssetImportService $importService)
    {
        $this->importService = $importService;
    }

    /**
     * AJAX: Parse uploaded XLSX/CSV file using native heuristic engine.
     * Returns JSON with cache key for the review page redirect.
     */
    public function parse(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,xlsx,txt|max:20480',
        ]);

        $file = $request->file('import_file');
        $realPath = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());

        // Normalize extension
        if (! in_array($extension, ['csv', 'xlsx'])) {
            $extension = 'csv'; // Fallback for .txt
        }

        try {
            // ═══════════════════════════════════════════
            // NATIVE HEURISTIC PARSE (zero external calls)
            // ═══════════════════════════════════════════
            $parsedData = $this->importService->parseFile($realPath, $extension);

            // ═══════════════════════════════════════════
            // GARBAGE COLLECTION — strict file deletion
            // ═══════════════════════════════════════════
            clearstatcache();
            if (file_exists($realPath)) {
                @unlink($realPath);
            }
            unset($file);

            // Store in cache for the review page
            $cacheKey = 'import_review_'.auth()->id();
            Cache::put($cacheKey, $parsedData, now()->addMinutes(30));

            $rowCount = count($parsedData);

            return response()->json([
                'success' => true,
                'cache_key' => $cacheKey,
                'row_count' => $rowCount,
                'redirect' => route('assets.import-review'),
            ]);

        } catch (\Exception $e) {
            Log::error('Import Parse Failure: '.$e->getMessage());

            // Backup garbage collection
            if (isset($realPath) && file_exists($realPath)) {
                @unlink($realPath);
            }

            return response()->json([
                'success' => false,
                'message' => __('assets.import_parse_error', ['message' => $e->getMessage()]),
            ], 422);
        }
    }

    /**
     * Review page: hydrate the bulk form from cached parsed data.
     */
    public function review(Request $request)
    {
        $cacheKey = 'import_review_'.auth()->id();
        $data = Cache::get($cacheKey);
        $warning = null;

        if ($data === null) {
            return redirect()->route('assets.index')
                ->with('warning', __('assets.import_parse_error', ['message' => 'Import session expired or not found.']));
        }

        if (empty($data)) {
            $data = array_fill(0, 5, [
                'tag' => '', 'name' => '', 'category_id' => '', 'department_id' => '',
                'status' => 'in_service', 'model' => '', 'serial_number' => '', 'purchase_date' => '',
            ]);
            $warning = __('assets.no_data_found');
        }

        $categories = \App\Models\Category::all();
        $departments = \App\Models\Department::all();

        return view('assets.import-review', compact('data', 'categories', 'departments', 'warning'));
    }

    /**
     * Manual bulk entry page: 5 blank rows.
     */
    public function bulkManual(Request $request)
    {
        $data = array_fill(0, 5, [
            'tag' => '', 'name' => '', 'category_id' => '', 'department_id' => '',
            'status' => 'in_service', 'model' => '', 'serial_number' => '', 'purchase_date' => '',
        ]);

        $categories = \App\Models\Category::all();
        $departments = \App\Models\Department::all();
        $warning = null;

        return view('assets.import-review', compact('data', 'categories', 'departments', 'warning'));
    }

    /**
     * Final DB insertion with strict Tenancy enforcement.
     */
    public function store(Request $request)
    {
        $request->validate([
            'assets' => 'required|array',
            'assets.*.name' => 'required|string|max:255',
            'assets.*.tag' => 'required|string|max:64',
            'assets.*.category_id' => 'required|exists:categories,id',
            'assets.*.department_id' => 'required|exists:departments,id',
            'assets.*.status' => 'required|in:in_service,out_of_service,disposed',
            'assets.*.model' => 'nullable|string|max:255',
            'assets.*.serial_number' => 'nullable|string|max:255',
            'assets.*.purchase_date' => 'nullable|date',
        ]);

        $assetsData = $request->input('assets');

        \DB::beginTransaction();
        try {
            foreach ($assetsData as $item) {
                \App\Models\Asset::create([
                    'name' => $item['name'],
                    'tag' => $item['tag'],
                    'category_id' => $item['category_id'],
                    'department_id' => $item['department_id'],
                    'status' => $item['status'],
                    'serial_number' => $item['serial_number'] ?? null,
                    'purchase_date' => $item['purchase_date'] ?? null,
                    'remarks' => ! empty($item['model']) ? 'Imported. Model: '.$item['model'] : 'Imported.',
                    'editor' => auth()->id(),
                ]);
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Bulk Insert Failed: '.$e->getMessage());

            return back()->withInput()->withErrors(['error' => 'Failed to save assets. '.$e->getMessage()]);
        }

        // Clean cache after successful import
        Cache::forget('import_review_'.auth()->id());

        return redirect()->route('assets.index')->with('ok', __('assets.import_success'));
    }
}
