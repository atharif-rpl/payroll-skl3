@extends('layouts.app')

@section('title', 'Hitung Gaji Karyawan')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Hitung Gaji Karyawan untuk Periode Tertentu</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.gaji.calculate.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="bulan" class="form-label">Bulan <span class="text-danger">*</span></label>
                    <select name="bulan" id="bulan" class="form-select @error('bulan') is-invalid @enderror" required>
                        @for ($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ old('bulan', date('m')) == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                    @error('bulan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                    <select name="tahun" id="tahun" class="form-select @error('tahun') is-invalid @enderror" required>
                        @for ($y=date('Y'); $y>=date('Y')-2; $y--)
                            <option value="{{ $y }}" {{ old('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    @error('tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                {{-- <div class="col-md-4 mb-3">
                    <label for="potongan_per_hari" class="form-label">Potongan per Hari Tidak Hadir (Rp) <span class="text-danger">*</span></label>
                    <input type="number" step="1000" class="form-control @error('potongan_per_hari') is-invalid @enderror" id="potongan_per_hari" name="potongan_per_hari" value="{{ old('potongan_per_hari', 50000) }}" required>
                     @error('potongan_per_hari') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div> --}}
            </div>
            <p class="text-muted">Sistem akan menghitung gaji untuk semua karyawan aktif pada periode yang dipilih berdasarkan data absensi. Pastikan data absensi sudah lengkap.</p>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin ingin menghitung dan menyimpan gaji untuk periode ini? Proses ini akan memperbarui data gaji yang sudah ada jika ada.')">Hitung dan Simpan Gaji</button>
                <a href="{{ route('admin.gaji.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection