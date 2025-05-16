<?php

// app/Http/Controllers/Admin/AbsensiController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function rekap(Request $request)
    {
        $query = Absensi::with('karyawan.user')->latest('tanggal');

        if ($request->filled('karyawan_id')) {
            $query->where('karyawan_id', $request->karyawan_id);
        }
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $absensis = $query->paginate(20);
        $karyawans = Karyawan::with('user')->get(); // Untuk filter dropdown

        return view('admin.absensi.rekap', compact('absensis', 'karyawans'));
    }

    // Anda bisa menambahkan method untuk CRUD absensi jika admin bisa mengubah data absensi
    // Misalnya, jika ada karyawan lupa absen, admin bisa input manual
}