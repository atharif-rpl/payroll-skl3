<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate; // Aktifkan jika Anda menggunakan Gate
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy', // Contoh, sesuaikan dengan model dan policy Anda
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Jika Anda menggunakan Gates, Anda bisa mendaftarkannya di sini
        // Gate::define('edit-settings', function ($user) {
        //     return $user->isAdmin;
        // });
    }
}