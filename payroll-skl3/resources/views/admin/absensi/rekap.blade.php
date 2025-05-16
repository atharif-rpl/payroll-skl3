@extends('layouts.app')

@section('title', 'Rekap Absensi Karyawan')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Rekap Absensi Karyawan</h4>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.absensi.rekap') }}" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="karyawan_id" class="form-label">Karyawan</label>
                    <select name="karyawan_id" id="karyawan_id" class="form-select">
                        <option value="">Semua Karyawan</option>
                        @foreach($karyawans as $k)
                            <option value="{{ $k->id }}" {{ request('karyawan_id') == $k->id ? 'selected' : '' }}>{{ $k->user->name }} ({{$k->nik}})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-md-3">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.absensi.rekap') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        @if($absensis->isEmpty())
            <div class="alert alert-info">Tidak ada data absensi yang cocok dengan filter.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Karyawan</th>
                            <th>NIK</th>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($absensis as $index => $absensi)
                        <tr>
                            <td>{{ $absensis->firstItem() + $index }}</td>
                            <td>{{ $absensi->karyawan->user->name }}</td>
                            <td>{{ $absensi->karyawan->nik }}</td>
                            <td>{{ \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('d M Y') }}</td>
                            <td>{{ $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i:s') : '-' }}</td>
                            <td>{{ $absensi->jam_pulang ? \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i:s') : '-' }}</td>
                            <td><span class="badge bg-{{ $absensi->status == 'hadir' ? 'success' : ($absensi->status == 'izin' || $absensi->status == 'sakit' ? 'warning' : 'danger') }}">{{ ucfirst($absensi->status) }}</span></td>
                            <td>{{ $absensi->keterangan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $absensis->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection