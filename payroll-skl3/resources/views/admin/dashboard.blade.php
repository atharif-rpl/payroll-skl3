@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3>Admin Dashboard</h3>
                <p>Selamat datang, {{ Auth::user()->name }}!</p>
            </div>
            <div class="card-body">
                <p>Gunakan menu navigasi di atas untuk mengelola sistem.</p>
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Kelola Karyawan</h5>
                                <p class="card-text">Tambah, lihat, edit, dan hapus data karyawan.</p>
                                <a href="{{ route('admin.karyawan.index') }}" class="btn btn-primary">Go to Karyawan</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Rekap Absensi</h5>
                                <p class="card-text">Lihat rekapitulasi absensi semua karyawan.</p>
                                <a href="{{ route('admin.absensi.rekap') }}" class="btn btn-info">Go to Absensi</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Kelola Gaji</h5>
                                <p class="card-text">Hitung dan lihat data gaji bulanan karyawan.</p>
                                <a href="{{ route('admin.gaji.index') }}" class="btn btn-success">Go to Gaji</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection