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

// Redirect root to login page to ensure login appears on application start
Route::get('/', function () {
    return redirect()->route('login');
});

// ===============================
// Authentication Routes
// ===============================
Route::middleware('guest')->group(function () {
    // Explicitly define login route
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard route handler that redirects based on user role
Route::get('/dashboard', function() {
    if (Auth::user()->role == 'admin') {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('karyawan.dashboard');
    }
})->middleware('auth')->name('dashboard');

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