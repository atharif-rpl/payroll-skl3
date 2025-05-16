<?php

// app/Providers/AppServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // Jika menggunakan versi Laravel lama untuk defaultStringLength
use Carbon\Carbon; // <-- TAMBAHKAN INI

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Schema::defaultStringLength(191); // Jika diperlukan untuk MySQL versi lama

        // Mengatur locale default untuk Carbon berdasarkan konfigurasi aplikasi
        try {
            Carbon::setLocale(config('app.locale')); // <-- TAMBAHKAN INI
        } catch (\Exception $e) {
            // Tangani jika locale tidak valid atau paket bahasa belum terinstal
            // Anda bisa log error ini
            // Log::error("Failed to set Carbon locale: " . $e->getMessage());
        }
    }
}