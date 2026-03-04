<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Services\TenantBackupService;
use App\Services\TenantRestoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BackupController extends Controller
{
    /**
     * Enforce authorisation AND a valid tenant context.
     *
     * - Any role='admin' user passes (their property_id is always set).
     * - A super admin MUST have switched to a specific property first.
     *   If they haven't, we redirect back with a flash warning.
     */
    private function guardTenantContext(): void
    {
        $user = Auth::user();

        if (! $user->isRole('admin') && ! $user->isSuperAdmin()) {
            abort(403, 'Unauthorized. Only administrators can perform backups.');
        }

        if ($user->isSuperAdmin() && ! session('active_property_id')) {
            session()->flash('warning', __('messages.backup_select_property_warning'));
            abort(redirect()->route('assets.index'));
        }
    }

    /**
     * Resolve the active Property model for the current session.
     * Super admin → from session; normal admin → from their profile.
     */
    private function resolveActiveProperty(): Property
    {
        $user = Auth::user();

        $propertyId = $user->isSuperAdmin()
            ? session('active_property_id')
            : $user->property_id;

        return Property::findOrFail($propertyId);
    }

    // ── Tenant-Aware Download ────────────────────────────────────────────────

    public function download(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $this->guardTenantContext();

        $property = $this->resolveActiveProperty();
        $service  = new TenantBackupService($property);
        $zipPath  = $service->build();

        $filename = 'NihamBackup-' . $property->code . '-' . now()->format('Y-m-d_H-i-s') . '.zip';

        return response()
            ->download($zipPath, $filename)
            ->deleteFileAfterSend(true);
    }

    // ── Tenant-Aware Restore ─────────────────────────────────────────────────

    public function restore(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->guardTenantContext();

        $request->validate([
            'backup' => 'required|file|mimes:zip|max:102400', // 100 MB cap
        ]);

        $property = $this->resolveActiveProperty();

        // Move the uploaded file to a known absolute path.
        // storeAs() uses the default disk (storage/app/private/ in Laravel 11+),
        // so we use getRealPath() to get the actual temp upload and copy manually.
        $uploadedFile     = $request->file('backup');
        $absoluteTempPath = storage_path('app/restore-upload-' . uniqid() . '.zip');
        copy($uploadedFile->getRealPath(), $absoluteTempPath);

        try {
            $service = new TenantRestoreService($property, $absoluteTempPath);
            $service->restore();
        } catch (\JsonException $e) {
            Log::error('Restore failed (JSON): ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('assets.index')->withErrors([
                'backup' => __('messages.restore_error_json') . ' ' . $e->getMessage(),
            ]);
        } catch (\RuntimeException $e) {
            Log::error('Restore failed (Runtime): ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('assets.index')->withErrors([
                'backup' => $e->getMessage(),
            ]);
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('assets.index')->withErrors([
                'backup' => __('messages.restore_error_generic') . ' ' . $e->getMessage(),
            ]);
        } finally {
            if (file_exists($absoluteTempPath)) {
                unlink($absoluteTempPath);
            }
        }

        return redirect()->route('assets.index')->with('ok', __('messages.restore_success'));
    }
}
