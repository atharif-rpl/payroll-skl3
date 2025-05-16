@extends('layouts.app')

@section('title', 'Detail Karyawan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Detail Karyawan: {{ $karyawan->user->name }}</h4>
        <a href="{{ route('admin.karyawan.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
    </div>
    <div class="card-body">
        <h5>Data Akun</h5>
        <table class="table table-bordered">
            <tr>
                <th width="200px">Nama Lengkap</th>
                <td>{{ $karyawan->user->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $karyawan->user->email }}</td>
            </tr>
            <tr>
                <th>Role</th>
                <td>{{ ucfirst($karyawan->user->role) }}</td>
            </tr>
        </table>

        <h5 class="mt-4">Data Karyawan</h5>
        <table class="table table-bordered">
            <tr>
                <th width="200px">NIK</th>
                <td>{{ $karyawan->nik }}</td>
            </tr>
            <tr>
                <th>Posisi</th>
                <td>{{ $karyawan->posisi }}</td>
            </tr>
            <tr>
                <th>Tanggal Masuk</th>
                <td>{{ \Carbon\Carbon::parse($karyawan->tanggal_masuk)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <th>Gaji Pokok</th>
                <td>Rp {{ number_format($karyawan->gaji_pokok, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>No. Telepon</th>
                <td>{{ $karyawan->no_telepon ?? '-' }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $karyawan->alamat ?? '-' }}</td>
            </tr>
             <tr>
                <th>Dibuat Pada</th>
                <td>{{ $karyawan->created_at->translatedFormat('d F Y H:i') }}</td>
            </tr>
            <tr>
                <th>Diperbarui Pada</th>
                <td>{{ $karyawan->updated_at->translatedFormat('d F Y H:i') }}</td>
            </tr>
        </table>
         <div class="mt-3">
            <a href="{{ route('admin.karyawan.edit', $karyawan->id) }}" class="btn btn-warning">Edit Karyawan</a>
        </div>
    </div>
</div>
@endsection