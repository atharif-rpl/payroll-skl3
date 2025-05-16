<?php

// app/Http/Controllers/Admin/GajiController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GajiController extends Controller
{
    public function index(Request $request)
    {
        $query = Gaji::with('karyawan.user')->orderBy('tahun', 'desc')->orderBy('bulan', 'desc');

        if ($request->filled('karyawan_id')) {
            $query->where('karyawan_id', $request->karyawan_id);
        }
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $gajis = $query->paginate(15);
        $karyawans = Karyawan::with('user')->get(); // Untuk filter

        return view('admin.gaji.index', compact('gajis', 'karyawans'));
    }

    public function showCalculationForm()
    {
        return view('admin.gaji.calculate_form');
    }

    public function calculateAndStore(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:' . (date('Y') - 5) . '|max:' . (date('Y') + 1),
            // 'potongan_per_hari' => 'required|numeric|min:0' // Tambahkan jika potongan per hari fix
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        // Asumsi sederhana potongan per hari tidak hadir (izin, sakit, alpa)
        // Anda bisa membuatnya lebih kompleks, misal dari konfigurasi atau input form
        // $potongan_per_hari = $request->potongan_per_hari ?: 50000; // Contoh

        $karyawans = Karyawan::whereHas('user', function($q){ // Hanya karyawan aktif
            $q->whereNotNull('email_verified_at'); // Contoh filter karyawan aktif
        })->get();

        if ($karyawans->isEmpty()) {
            return back()->with('error', 'Tidak ada karyawan aktif untuk dihitung gajinya.');
        }

        DB::beginTransaction();
        try {
            foreach ($karyawans as $karyawan) {
                $absensiBulanIni = Absensi::where('karyawan_id', $karyawan->id)
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->get();

                $total_hadir = $absensiBulanIni->where('status', 'hadir')->count();
                $total_izin = $absensiBulanIni->where('status', 'izin')->count();
                $total_sakit = $absensiBulanIni->where('status', 'sakit')->count();
                $total_tanpa_keterangan = $absensiBulanIni->where('status', 'tanpa keterangan')->count();

                // Hitung jumlah hari kerja dalam sebulan (misal, Senin-Jumat)
                // Ini bisa lebih kompleks, untuk sementara anggap 22 hari kerja standar
                $hariKerjaSebulan = 22; // Ini perlu disesuaikan atau dihitung dinamis

                // Potongan = (Gaji Pokok / Hari Kerja Sebulan) * Jumlah Hari Tidak Hadir (selain 'hadir')
                $hariTidakMasukBerbayar = $total_izin + $total_sakit + $total_tanpa_keterangan;

                // Logika potongan bisa beragam. Contoh:
                // 1. Potongan proporsional untuk semua jenis ketidakhadiran
                // $potongan = ($karyawan->gaji_pokok / $hariKerjaSebulan) * $hariTidakMasukBerbayar;
                // 2. Potongan hanya untuk 'tanpa keterangan'
                $potongan = ($karyawan->gaji_pokok / $hariKerjaSebulan) * $total_tanpa_keterangan;
                // 3. Potongan dengan rate berbeda per jenis ketidakhadiran (lebih kompleks)

                $gaji_bersih = $karyawan->gaji_pokok - $potongan;
                if ($gaji_bersih < 0) $gaji_bersih = 0; // Gaji tidak boleh minus

                Gaji::updateOrCreate(
                    [
                        'karyawan_id' => $karyawan->id,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                    ],
                    [
                        'total_hadir' => $total_hadir,
                        'total_izin' => $total_izin,
                        'total_sakit' => $total_sakit,
                        'total_tanpa_keterangan' => $total_tanpa_keterangan,
                        'gaji_pokok' => $karyawan->gaji_pokok,
                        'potongan' => $potongan,
                        'gaji_bersih' => $gaji_bersih,
                        'keterangan' => 'Gaji periode ' . Carbon::create()->month($bulan)->translatedFormat('F') . ' ' . $tahun,
                        // 'tanggal_pembayaran' => Carbon::createFromDate($tahun, $bulan, 25)->toDateString(), // Contoh
                    ]
                );
            }
            DB::commit();
            return redirect()->route('admin.gaji.index')->with('success', 'Perhitungan gaji untuk periode ' . Carbon::create()->month($bulan)->translatedFormat('F') . ' ' . $tahun . ' berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menghitung gaji: ' . $e->getMessage());
        }
    }
    // Method show, edit, update, destroy untuk Gaji jika diperlukan
}
