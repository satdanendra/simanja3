<?php

use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Routes untuk manajemen pegawai (hanya superadmin)
    Route::prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/', [PegawaiController::class, 'index'])->name('index');
        Route::get('/create', [PegawaiController::class, 'create'])->name('create');
        Route::post('/', [PegawaiController::class, 'store'])->name('store');
        Route::get('/{pegawai}', [PegawaiController::class, 'show'])->name('show');
        Route::get('/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('edit');
        Route::put('/{pegawai}', [PegawaiController::class, 'update'])->name('update');
        Route::delete('/{pegawai}', [PegawaiController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [PegawaiController::class, 'restore'])->name('restore');
        
        // Import routes
        Route::get('/import/form', [PegawaiController::class, 'importForm'])->name('import.form');
        Route::post('/import', [PegawaiController::class, 'import'])->name('import');
        Route::get('/import/results', [PegawaiController::class, 'importResults'])->name('import.results');
        Route::get('/import/download-error-report', [PegawaiController::class, 'downloadErrorReport'])->name('import.download-error-report');
        Route::get('/import/download-template', [PegawaiController::class, 'downloadTemplate'])->name('import.download-template');
    });
});

require __DIR__.'/auth.php';
