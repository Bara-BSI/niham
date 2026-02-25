<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;

class BackupController extends Controller
{
    public function download()
    {
        if (!\Illuminate\Support\Facades\Auth::user()->isRole('admin') && !\Illuminate\Support\Facades\Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized. Only administrators can perform backups.');
        }

        $filename = 'NihamBackup-'.now()->format('Y-m-d_H-i-s').'.zip';
        $zipPath = storage_path("app/$filename");

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            // 1. Dump database
            $dbFile = storage_path('app/db-backup.sql');
            $this->dumpDatabase($dbFile);
            $zip->addFile($dbFile, 'db-backup.sql');

            // 2. Add attachments folder
            $attachmentsPath = storage_path('app/public/attachments');
            $this->addFolderToZip($attachmentsPath, $zip, 'attachments');

            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function restore(Request $request)
    {
        if (!\Illuminate\Support\Facades\Auth::user()->isRole('admin') && !\Illuminate\Support\Facades\Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized. Only administrators can restore backups.');
        }

        $request->validate([
            'backup' => 'required|file|mimes:zip',
        ]);

        $file = $request->file('backup');
        $zip = new ZipArchive;

        if ($zip->open($file->getRealPath()) === true) {
            $extractPath = storage_path('app/restore-temp');
            $zip->extractTo($extractPath);
            $zip->close();

            // 1. Restore database
            $dbFile = $extractPath.'/db-backup.sql';
            if (file_exists($dbFile)) {
                $connection = config('database.connections.mysql');
                $command = sprintf(
                    'mysql --user=%s --password=%s --host=%s %s < %s',
                    $connection['username'],
                    $connection['password'],
                    $connection['host'],
                    $connection['database'],
                    $dbFile
                );
                exec($command);
            }

            // 2. Restore attachments
            $attachmentsPath = $extractPath.'/attachments';
            if (is_dir($attachmentsPath)) {
                $targetPath = storage_path('app/public/attachments');
                // Clear old attachments
                \File::deleteDirectory($targetPath);
                // Copy new ones
                \File::copyDirectory($attachmentsPath, $targetPath);
            }

            // Cleanup
            \File::deleteDirectory($extractPath);

            return back()->with('ok', 'Backup restored successfully. All data has been replaced.');
        }

        return back()->withErrors(['backup' => 'Failed to open backup file.']);
    }

    private function dumpDatabase($outputFile)
    {
        $connection = config('database.connections.mysql');
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            $connection['username'],
            $connection['password'],
            $connection['host'],
            $connection['database'],
            $outputFile
        );
        exec($command);
    }

    private function addFolderToZip($folder, ZipArchive $zip, $zipFolder)
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folder),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (! $file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = $zipFolder.'/'.substr($filePath, strlen($folder) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
    }
}
