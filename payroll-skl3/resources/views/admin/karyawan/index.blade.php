@extends('layouts.app')

@section('title', 'Kelola Karyawan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Data Karyawan</h4>
        <a href="{{ route('admin.karyawan.create') }}" class="btn btn-primary">Tambah Karyawan</a>
    </div>
    <div class="card-body">
        @if($karyawans->isEmpty())
            <div class="alert alert-info">Belum ada data karyawan.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Posisi</th>
                            <th>Tgl Masuk</th>
                            <th>Gaji Pokok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($karyawans as $index => $karyawan)
                        <tr>
                            <td>{{ $karyawans->firstItem() + $index }}</td>
                            <td>{{ $karyawan->nik }}</td>
                            <td>{{ $karyawan->user->name }}</td>
                            <td>{{ $karyawan->user->email }}</td>
                            <td>{{ $karyawan->posisi }}</td>
                            <td>{{ \Carbon\Carbon::parse($karyawan->tanggal_masuk)->translatedFormat('d M Y') }}</td>
                            <td>Rp {{ number_format($karyawan->gaji_pokok, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('admin.karyawan.show', $karyawan->id) }}" class="btn btn-sm btn-info" title="Lihat"><i class="bi bi-eye"></i>Lihat</a>
                                <a href="{{ route('admin.karyawan.edit', $karyawan->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i>Edit</a>
                                <form action="{{ route('admin.karyawan.destroy', $karyawan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus karyawan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i>Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $karyawans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush