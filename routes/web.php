<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\Asset;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('assets.index');
});



Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Export the assets
    Route::get('/assets/export', [AssetController::class, 'export'])->name('assets.export');

    Route::resource('assets', AssetController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);

    Route::get('assets/{asset}/qr', [QrController::class, 'image'])->name('assets.qr');

    // Halaman scan qr
    Route::view('scan', 'qr.scan')->name('qr.scan');


    
    // Backup routes
    Route::post('/backup/download', [BackupController::class, 'download'])->name('backup.download');
    Route::post('/backup/restore', [BackupController::class, 'restore'])->name('backup.restore');
});

// Public signed resolution endpoint (no auth)
Route::get('/qr/resolve/{uuid}', [QrController::class, 'resolve'])
    ->name('qr.resolve');

require __DIR__.'/auth.php';
