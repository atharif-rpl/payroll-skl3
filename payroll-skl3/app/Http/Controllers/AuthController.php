<?php

// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm() // PASTIKAN METHOD INI ADA
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role == 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->role == 'karyawan') {
                return redirect()->intended(route('karyawan.dashboard'));
            }
            return redirect()->intended('/'); // Fallback
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nik' => ['nullable', 'string', 'max:20', 'unique:karyawan,nik'],
            'posisi' => ['nullable', 'string', 'max:100'],
            'no_telepon' => ['nullable', 'string', 'max:15'],
            'alamat' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')
                        ->withErrors($validator)
                        ->withInput();
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'karyawan',
            ]);

            $karyawanDataInput = [
                'nik' => $request->nik,
                'posisi' => $request->posisi,
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat,
                'tanggal_masuk' => now(),
                'gaji_pokok' => 0,
            ];

            // Filter out null values so only provided data is passed to create
            $karyawanDataFiltered = array_filter($karyawanDataInput, function($value) {
                return !is_null($value) && $value !== ''; // Juga cek string kosong
            });


            // Hanya buat data karyawan jika ada field karyawan yang diisi
            if (!empty($karyawanDataFiltered) && (isset($karyawanDataFiltered['nik']) || isset($karyawanDataFiltered['posisi']))) {
                 // Pastikan setidaknya NIK atau Posisi ada sebelum membuat record Karyawan
                $user->karyawan()->create($karyawanDataFiltered);
            }


            DB::commit();
            Auth::login($user);
            return redirect()->route('karyawan.dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('Registrasi gagal: ' . $e->getMessage()); // Aktifkan logging jika perlu
            \Illuminate\Support\Facades\Log::error('Registrasi gagal: '. $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return redirect()->route('register')
                        ->with('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi. Error: ' . $e->getMessage()) // Tampilkan pesan error untuk debug
                        ->withInput();
        }
    }
}