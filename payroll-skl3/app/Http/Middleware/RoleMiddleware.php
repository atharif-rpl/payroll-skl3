<?php

// app/Http/Middleware/RoleMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response; // Untuk Laravel 10+

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) { // Jika belum login
            return redirect('login');
        }

        $user = Auth::user();
        if (!in_array($user->role, $roles)) {
            // Redirect ke halaman yang sesuai atau kembalikan error
            if ($user->role === 'karyawan') {
                return redirect()->route('karyawan.dashboard')->with('error', 'Akses ditolak.');
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
            }
            // Default fallback
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}