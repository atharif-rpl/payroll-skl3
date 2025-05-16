<?php

// app/Http/Controllers/KaryawanPageController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;

class KaryawanPageController extends Controller
{
    public function dashboard()
    {
        $karyawan = Auth::user()->karyawan;
        if (!$karyawan) {
            // Handle jika user karyawan tidak punya data karyawan terkait
            Auth::logout();
            return redirect('/login')->with('error', 'Data karyawan tidak ditemukan.');
        }

        $today = Carbon::today()->toDateString();
        $absensiHariIni = Absensi::where('karyawan_id', $karyawan->id)
                                ->where('tanggal', $today)
                                ->first();
        // Kirim data ke view dashboard karyawan
        return view('karyawan.dashboard', compact('karyawan', 'absensiHariIni'));
    }

    public function presensiMasuk(Request $request)
    {
        $karyawan = Auth::user()->karyawan;
        $today = Carbon::today();

        $absensi = Absensi::firstOrNew([
            'karyawan_id' => $karyawan->id,
            'tanggal' => $today->toDateString(),
        ]);

        if ($absensi->jam_masuk) {
            return redirect()->route('karyawan.dashboard')->with('error', 'Anda sudah melakukan presensi masuk hari ini.');
        }

        $absensi->jam_masuk = Carbon::now()->toTimeString();
        $absensi->status = 'hadir'; // Default status saat masuk
        $absensi->save();

        return redirect()->route('karyawan.dashboard')->with('success', 'Presensi masuk berhasil dicatat.');
    }

    public function presensiPulang(Request $request)
    {
        $karyawan = Auth::user()->karyawan;
        $today = Carbon::today()->toDateString();

        $absensi = Absensi::where('karyawan_id', $karyawan->id)
                            ->where('tanggal', $today)
                            ->first();

        if (!$absensi || !$absensi->jam_masuk) {
            return redirect()->route('karyawan.dashboard')->with('error', 'Anda belum melakukan presensi masuk hari ini.');
        }

        if ($absensi->jam_pulang) {
            return redirect()->route('karyawan.dashboard')->with('error', 'Anda sudah melakukan presensi pulang hari ini.');
        }

        $absensi->jam_pulang = Carbon::now()->toTimeString();
        $absensi->save();

        return redirect()->route('karyawan.dashboard')->with('success', 'Presensi pulang berhasil dicatat.');
    }

    public function riwayatAbsensi()
    {
        $karyawan = Auth::user()->karyawan;
        $riwayat = Absensi::where('karyawan_id', $karyawan->id)
                            ->orderBy('tanggal', 'desc')
                            ->paginate(10); // Paginasi untuk data yang banyak

        return view('karyawan.riwayat_absensi', compact('riwayat'));
    }
}