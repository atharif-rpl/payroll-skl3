<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanPageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Admin\GajiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sinilah Anda dapat mendaftarkan rute web untuk aplikasi Anda.
| Rute ini dimuat oleh RouteServiceProvider dalam grup "web".
|
*/

// Route::get('/', function () {
//     return 'Aplikasi Laravel Berjalan!';
// });

// ===============================
// Authentication Routes
// ===============================
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ===============================
// Karyawan Routes
// ===============================
Route::middleware(['auth', 'role:karyawan'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {
        Route::get('dashboard', [KaryawanPageController::class, 'dashboard'])->name('dashboard');
        Route::post('presensi-masuk', [KaryawanPageController::class, 'presensiMasuk'])->name('presensi.masuk');
        Route::post('presensi-pulang', [KaryawanPageController::class, 'presensiPulang'])->name('presensi.pulang');
        Route::get('riwayat-absensi', [KaryawanPageController::class, 'riwayatAbsensi'])->name('riwayat.absensi');
    });

// ===============================
// Admin Routes
// ===============================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('karyawan', KaryawanController::class);
        
        Route::get('absensi/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');

        Route::get('gaji', [GajiController::class, 'index'])->name('gaji.index');
        Route::get('gaji/calculate', [GajiController::class, 'showCalculationForm'])->name('gaji.calculate.form');
        Route::post('gaji/calculate', [GajiController::class, 'calculateAndStore'])->name('gaji.calculate.store');
    });
