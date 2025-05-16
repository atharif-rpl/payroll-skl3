@extends('layouts.app')

@section('title', 'Data Gaji Karyawan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Data Gaji Karyawan</h4>
        <a href="{{ route('admin.gaji.calculate.form') }}" class="btn btn-success">Hitung Gaji Baru</a>
    </div>
    <div class="card-body">
         <form method="GET" action="{{ route('admin.gaji.index') }}" class="mb-4">
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
                <div class="col-md-2">
                    <label for="bulan" class="form-label">Bulan</label>
                    <select name="bulan" id="bulan" class="form-select">
                        <option value="">Semua</option>
                        @for ($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="tahun" class="form-label">Tahun</label>
                    <select name="tahun" id="tahun" class="form-select">
                        <option value="">Semua</option>
                        @for ($y=date('Y'); $y>=date('Y')-5; $y--)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.gaji.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        @if($gajis->isEmpty())
            <div class="alert alert-info">Tidak ada data gaji yang cocok dengan filter.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Karyawan</th>
                            <th>Periode</th>
                            <th>Gaji Pokok</th>
                            <th>Potongan</th>
                            <th>Gaji Bersih</th>
                            <th>Tgl Bayar</th>
                            <th>Keterangan</th>
                            {{-- <th>Aksi</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gajis as $index => $gaji)
                        <tr>
                            <td>{{ $gajis->firstItem() + $index }}</td>
                            <td>{{ $gaji->karyawan->user->name }}</td>
                            <td>{{ \Carbon\Carbon::create()->month($gaji->bulan)->translatedFormat('F') }} {{ $gaji->tahun }}</td>
                            <td>Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($gaji->potongan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($gaji->gaji_bersih, 0, ',', '.') }}</td>
                            <td>{{ $gaji->tanggal_pembayaran ? \Carbon\Carbon::parse($gaji->tanggal_pembayaran)->translatedFormat('d M Y') : '-' }}</td>
                            <td>{{ $gaji->keterangan ?? '-' }}</td>
                            {{-- <td>
                                <a href="#" class="btn btn-sm btn-info">Detail</a>
                            </td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $gajis->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection