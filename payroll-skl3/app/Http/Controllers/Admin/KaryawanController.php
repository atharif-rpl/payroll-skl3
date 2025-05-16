<?php

// app/Http/Controllers/Admin/KaryawanController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Untuk transaction

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::with('user')->latest()->paginate(10);
        return view('admin.karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        return view('admin.karyawan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nik' => 'required|string|max:20|unique:karyawan,nik',
            'posisi' => 'required|string|max:100',
            'tanggal_masuk' => 'required|date',
            'gaji_pokok' => 'required|numeric|min:0',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:15',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'karyawan',
            ]);

            $user->karyawan()->create([
                'nik' => $request->nik,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'posisi' => $request->posisi,
                'tanggal_masuk' => $request->tanggal_masuk,
                'gaji_pokok' => $request->gaji_pokok,
            ]);

            DB::commit();
            return redirect()->route('admin.karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan karyawan: ' . $e->getMessage());
        }
    }

    public function show(Karyawan $karyawan) // Route model binding
    {
        $karyawan->load('user'); // Eager load user
        return view('admin.karyawan.show', compact('karyawan'));
    }

    public function edit(Karyawan $karyawan)
    {
        $karyawan->load('user');
        return view('admin.karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
         $request->validate([
            'name' => 'required|string|max:255',
            // Email unik, kecuali untuk user saat ini
            'email' => 'required|string|email|max:255|unique:users,email,' . $karyawan->user_id,
            'password' => 'nullable|string|min:8|confirmed', // Password opsional
            'nik' => 'required|string|max:20|unique:karyawan,nik,' . $karyawan->id,
            'posisi' => 'required|string|max:100',
            'tanggal_masuk' => 'required|date',
            'gaji_pokok' => 'required|numeric|min:0',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:15',
        ]);

        DB::beginTransaction();
        try {
            $user = $karyawan->user;
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $karyawan->update([
                'nik' => $request->nik,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'posisi' => $request->posisi,
                'tanggal_masuk' => $request->tanggal_masuk,
                'gaji_pokok' => $request->gaji_pokok,
            ]);

            DB::commit();
            return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui data karyawan: ' . $e->getMessage());
        }
    }

    public function destroy(Karyawan $karyawan)
    {
        DB::beginTransaction();
        try {
            // Hapus data karyawan akan otomatis menghapus user jika onDelete('cascade') diset di migration user_id
            // Jika tidak, hapus user secara manual
            $karyawan->user()->delete(); // Ini akan menghapus user, dan karyawan akan terhapus karena cascade
            // Jika tidak ada cascade pada user_id di karyawan, maka:
            // $karyawan->delete(); // Hapus karyawan dulu
            // $user->delete(); // Kemudian hapus user

            DB::commit();
            return redirect()->route('admin.karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.karyawan.index')->with('error', 'Gagal menghapus karyawan: ' . $e->getMessage());
        }
    }
}
