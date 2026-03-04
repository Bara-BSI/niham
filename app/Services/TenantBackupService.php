<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Department;
use App\Models\Property;
use App\Models\Role;
use App\Models\Scopes\PropertyScope;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

/**
 * Tenant-Aware Backup Service
 *
 * Serialises all data belonging to a single Property into a portable .zip file.
 * The exported JSON never contains a `property_id` column so the archive is safe
 * to import into any tenant context — the restore service re-injects it.
 *
 * Zip structure:
 *   manifest.json                  — metadata / schema version
 *   data.json                      — all model data (no property_id)
 *   media/branding/{filename}      — logo & background image
 *   media/attachments/{filename}   — asset attachment files
 */
class TenantBackupService
{
    public function __construct(private readonly Property $property) {}

    /**
     * Build the zip and return its absolute path on disk.
     *
     * @throws \RuntimeException if the archive cannot be created.
     */
    public function build(): string
    {
        $filename = 'NihamBackup-'.now()->format('Y-m-d_H-i-s').'.zip';
        $zipPath = storage_path("app/{$filename}");

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException("Could not create ZIP archive at [{$zipPath}].");
        }

        try {
            // ── 1. Manifest ──────────────────────────────────────────────────
            $zip->addFromString(
                'manifest.json',
                json_encode($this->buildManifest(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)
            );

            // ── 2. Model data ────────────────────────────────────────────────
            $data = $this->buildDataPayload();
            $zip->addFromString(
                'data.json',
                json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)
            );

            // ── 3. Media files ───────────────────────────────────────────────
            $this->bundleMediaFiles($zip, $data);

        } finally {
            $zip->close();
        }

        return $zipPath;
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    /** @return array<string, mixed> */
    private function buildManifest(): array
    {
        return [
            'niham_schema_version' => 1,
            'exported_at' => now()->toIso8601String(),
            'property_uuid' => $this->property->uuid,
            'property_name' => $this->property->name,
            'property_code' => $this->property->code,
        ];
    }

    /** @return array<string, mixed> */
    private function buildDataPayload(): array
    {
        return [
            'property' => $this->exportProperty(),
            'roles' => $this->exportRoles(),
            'departments' => $this->exportDepartments(),
            'categories' => $this->exportCategories(),
            'assets' => $this->exportAssets(),
        ];
    }

    /**
     * Export property settings.
     * The Property table has no `property_id`; we only strip the numeric `id`
     * so the UUID can still be used as a human-readable reference in the archive.
     *
     * @return array<string, mixed>
     */
    private function exportProperty(): array
    {
        return $this->omit($this->property->toArray(), ['id']);
    }

    /**
     * Export all Roles belonging to this property, stripping `property_id`.
     *
     * @return list<array<string, mixed>>
     */
    private function exportRoles(): array
    {
        return Role::withoutGlobalScope(PropertyScope::class)
            ->where('property_id', $this->property->id)
            ->get()
            ->map(fn (Role $r) => $this->omit($r->toArray(), ['id', 'property_id']))
            ->values()
            ->toArray();
    }

    /**
     * Export all Departments belonging to this property, stripping `property_id`.
     *
     * @return list<array<string, mixed>>
     */
    private function exportDepartments(): array
    {
        return Department::withoutGlobalScope(PropertyScope::class)
            ->where('property_id', $this->property->id)
            ->get()
            ->map(fn (Department $d) => $this->omit($d->toArray(), ['id', 'property_id']))
            ->values()
            ->toArray();
    }

    /**
     * Export all Categories belonging to this property, stripping `property_id`.
     *
     * @return list<array<string, mixed>>
     */
    private function exportCategories(): array
    {
        return Category::withoutGlobalScope(PropertyScope::class)
            ->where('property_id', $this->property->id)
            ->get()
            ->map(fn (Category $c) => $this->omit($c->toArray(), ['id', 'property_id']))
            ->values()
            ->toArray();
    }

    /**
     * Export all Assets (including soft-deleted) for this property.
     *
     * - Strips `id`, `property_id`, numeric `category_id`, `department_id`, and `editor`.
     * - Adds `category_uuid` / `department_uuid` so the restore service can re-resolve
     *   the correct local numeric IDs regardless of which property is the target.
     * - Nests the single Attachment record (hasOne) as `attachment`.
     * - Nests all AssetHistory records (hasMany) as `histories`.
     * - Both nested structures omit their own `id` and `asset_id` (the numeric FK).
     *   On restore the FK is re-resolved against the newly inserted asset PK.
     *
     * @return list<array<string, mixed>>
     */
    private function exportAssets(): array
    {
        return Asset::withoutGlobalScope(PropertyScope::class)
            ->withTrashed()
            ->where('property_id', $this->property->id)
            ->with(['attachments', 'histories', 'category', 'department'])
            ->get()
            ->map(function (Asset $asset): array {
                // Base row — strip numeric id, property_id, numeric FK columns,
                // and the eager-loaded relation arrays (re-added below).
                $row = $this->omit(
                    $asset->toArray(),
                    ['id', 'property_id', 'category_id', 'department_id', 'editor',
                        'attachments', 'histories', 'category', 'department']
                );

                // UUID-based relation references (portable across tenants)
                $row['category_uuid'] = optional($asset->category)->uuid;
                $row['department_uuid'] = optional($asset->department)->uuid;

                // Attachment (hasOne → single model or null)
                $row['attachment'] = $asset->attachments instanceof \App\Models\Attachment
                    ? $this->omit($asset->attachments->toArray(), ['id', 'asset_id'])
                    : null;

                // Histories (hasMany → collection)
                $row['histories'] = $asset->histories
                    ->map(fn ($h) => $this->omit($h->toArray(), ['id', 'asset_id', 'user_id']))
                    ->values()
                    ->toArray();

                return $row;
            })
            ->values()
            ->toArray();
    }

    /**
     * Walk the exported data payload and copy any referenced media files into
     * the zip under the `media/` prefix, preserving the relative storage path
     * so the restore service can locate them unambiguously.
     *
     * Layout inside zip:
     *   media/branding/{hash}.jpg         ← property logo / background
     *   media/attachments/{filename}.pdf  ← asset attachments
     *
     * @param  array<string, mixed>  $data
     */
    private function bundleMediaFiles(ZipArchive $zip, array $data): void
    {
        $disk = Storage::disk('public');

        // ── Property branding images ─────────────────────────────────────────
        foreach (['logo_path', 'background_image_path'] as $field) {
            $storedPath = $data['property'][$field] ?? null;

            if (is_string($storedPath) && $storedPath !== '' && $disk->exists($storedPath)) {
                $zip->addFile($disk->path($storedPath), "media/{$storedPath}");
            }
        }

        // ── Asset attachment files ───────────────────────────────────────────
        foreach ($data['assets'] as $assetRow) {
            $attachPath = $assetRow['attachment']['path'] ?? null;

            if (is_string($attachPath) && $attachPath !== '' && $disk->exists($attachPath)) {
                $zip->addFile($disk->path($attachPath), "media/{$attachPath}");
            }
        }
    }

    /**
     * Return a copy of `$data` with the specified `$keys` removed.
     *
     * @param  array<string, mixed>  $data
     * @param  list<string>  $keys
     * @return array<string, mixed>
     */
    private function omit(array $data, array $keys): array
    {
        return array_diff_key($data, array_flip($keys));
    }
}
