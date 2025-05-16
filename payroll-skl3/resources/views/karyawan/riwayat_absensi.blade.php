@extends('layouts.app')

@section('title', 'Riwayat Absensi Saya')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Riwayat Absensi Saya</h4>
    </div>
    <div class="card-body">
        @if($riwayat->isEmpty())
            <div class="alert alert-info">Belum ada data riwayat absensi.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayat as $absensi)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('d F Y') }}</td>
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
                {{ $riwayat->links() }}
            </div>
        @endif
    </div>
</div>
@endsection