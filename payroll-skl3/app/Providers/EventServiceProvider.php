<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        // Tambahkan event dan listener kustom Anda di sini
        // \App\Events\SomeEvent::class => [
        //     \App\Listeners\SomeEventListener::class,
        // ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     * Laravel akan otomatis menemukan event dan listener jika method ini mengembalikan true
     * dan listener Anda ditempatkan di direktori App\Listeners serta mengikuti konvensi penamaan.
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return false; // Ubah menjadi true jika Anda ingin menggunakan auto-discovery
    }
}