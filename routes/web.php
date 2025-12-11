<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TruckMappingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\UploadController;
use App\Models\ManifestUpload;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/uploads/{upload}/download/{file}', function (ManifestUpload $upload, $file) {
    // Require authentication and ensure the current user owns the upload.
    if (! auth()->check() || $upload->user_id !== auth()->id()) {
        abort(403);
    }
    if ($file === 'file1' && $upload->file1_path) {
        return Storage::download($upload->file1_path, $upload->file1_name);
    }
    if ($file === 'file2' && $upload->file2_path) {
        return Storage::download($upload->file2_path, $upload->file2_name);
    }
    // Combined manifest download
    if ($file === 'combined' && ($upload->combined_file_path ?? false)) {
        $downloadName = basename($upload->combined_file_path);
        return Storage::download($upload->combined_file_path, $downloadName);
    }
    abort(404);
})->name('uploads.download')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Dashboard (Authenticated)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Uploads
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('uploads')->name('uploads.')->group(function () {

    Route::get('/', [UploadController::class, 'index'])->name('index');
    Route::get('/new', [UploadController::class, 'create'])->name('create');
    Route::post('/detect', [UploadController::class, 'detect'])->name('detect');
    Route::post('/new', [UploadController::class, 'store'])->name('store');
    Route::get('/{upload}', [UploadController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| Reports
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'can:viewReports'])
    ->prefix('reports')
    ->name('reports.')
    ->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/distribution', [ReportController::class, 'distribution'])->name('distribution');
        Route::get('/distribution/export/xlsx', [ReportController::class, 'distributionExportXlsx'])->name('distribution.export.xlsx');
        Route::get('/distribution/export/pdf', [ReportController::class, 'distributionExportPdf'])->name('distribution.export.pdf');

        Route::get('/truck-summary', [ReportController::class, 'truckSummary'])->name('truck-summary');
        Route::get('/truck-summary/export/xlsx', [ReportController::class, 'truckSummaryExportXlsx'])->name('truck-summary.export.xlsx');
        Route::get('/truck-summary/export/pdf', [ReportController::class, 'truckSummaryExportPdf'])->name('truck-summary.export.pdf');

        Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::resource('truck-mappings', TruckMappingController::class)
        ->except(['show']);

    // Admin user management
    Route::resource('users', AdminUserController::class)->except(['show']);
});

require __DIR__.'/auth.php';
