<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;


// Route default (opsional)
Route::get('/', function () {
    return redirect()->route('dashboard'); // langsung ke dashboard
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Dosen
Route::get('/dosen', [DosenController::class, 'index'])->name('dosen');
Route::get('/tambahdosen', [DosenController::class, 'create']);
Route::post('/simpandosen', [DosenController::class, 'store'])->name('dosen.store');
Route::get('/editdosen/{nidn}', [DosenController::class, 'edit']);
Route::put('/updatedosen/{nidn}', [DosenController::class, 'update']);
Route::delete('/hapusdosen/{nidn}', [DosenController::class, 'destroy']);
// Mahasiswa
Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa');
Route::get('/tambahmahasiswa', [MahasiswaController::class, 'create']);
Route::post('/simpanmahasiswa', [DosenController::class, 'store'])->name('mahasiswa.store');
Route::get('/editmahasiswa/{nim}', [DosenController::class, 'edit']);
Route::put('/updatemahasiswa/{nim}', [DosenController::class, 'update']);
Route::delete('/hapusmahasiswa/{nim}', [DosenController::class, 'destroy']);