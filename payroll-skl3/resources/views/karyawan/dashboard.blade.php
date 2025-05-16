@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3>Selamat Datang, {{ Auth::user()->name }}!</h3>
                <p>Data Karyawan: {{ $karyawan->posisi }} - NIK: {{ $karyawan->nik ?? 'Belum diisi' }}</p>
            </div>
            <div class="card-body">
                <h4>Presensi Hari Ini ({{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }})</h4>
                <hr>
                @if($absensiHariIni && $absensiHariIni->jam_masuk && $absensiHariIni->jam_pulang)
                    <div class="alert alert-success">Anda sudah melakukan presensi masuk dan pulang hari ini.</div>
                    <p>Jam Masuk: {{ \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i:s') }}</p>
                    <p>Jam Pulang: {{ \Carbon\Carbon::parse($absensiHariIni->jam_pulang)->format('H:i:s') }}</p>
                @elseif($absensiHariIni && $absensiHariIni->jam_masuk)
                    <div class="alert alert-info">Anda sudah melakukan presensi masuk pada pukul {{ \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i:s') }}. Silakan lakukan presensi pulang.</div>
                    <form action="{{ route('karyawan.presensi.pulang') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">Presensi Pulang</button>
                    </form>
                @else
                     <div class="alert alert-warning">Anda belum melakukan presensi masuk hari ini.</div>
                    <form action="{{ route('karyawan.presensi.masuk') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">Presensi Masuk</button>
                    </form>
                @endif

                @if($absensiHariIni && $absensiHariIni->status != 'hadir')
                    <p class="mt-3">Status hari ini: <strong>{{ ucfirst($absensiHariIni->status) }}</strong></p>
                    @if($absensiHariIni->keterangan)
                    <p>Keterangan: {{ $absensiHariIni->keterangan }}</p>
                    @endif
                @endif

                <hr>
                <a href="{{ route('karyawan.riwayat.absensi') }}" class="btn btn-secondary mt-3">Lihat Riwayat Absensi Saya</a>
            </div>
        </div>
    </div>
</div>
@endsection