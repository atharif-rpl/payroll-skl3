<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard'; // Sesuaikan dengan rute home Anda, bisa '/karyawan/dashboard' atau '/admin/dashboard'

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers'; // Ini tidak lagi direkomendasikan di Laravel 8+
                                                       // Sebaiknya gunakan FQCN di file rute Anda

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    
        $this->routes(function () {
            // Route::middleware('api') // <-- KOMENTARI ATAU HAPUS BAGIAN INI
            //     ->prefix('api')
            //     // ->namespace($this->namespace)
            //     ->group(base_path('routes/api.php')); // <-- KOMENTARI ATAU HAPUS BAGIAN INI
    
            Route::middleware('web')
                // ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}