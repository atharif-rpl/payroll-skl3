@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ __('Register Karyawan Baru') }}</div>

            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <h5>Informasi Akun</h5>
                    <div class="row mb-3">
                        <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Nama Lengkap') }} <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Alamat Email') }} <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }} <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Konfirmasi Password') }} <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <hr>
                    <h5>Informasi Karyawan (Opsional saat registrasi publik, bisa diisi admin nanti)</h5>

                    <div class="row mb-3">
                        <label for="nik" class="col-md-4 col-form-label text-md-end">{{ __('NIK') }}</label>
                        <div class="col-md-6">
                            <input id="nik" type="text" class="form-control @error('nik') is-invalid @enderror" name="nik" value="{{ old('nik') }}" autocomplete="nik">
                             @error('nik')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">Bisa diisi nanti oleh Admin jika Anda belum memilikinya.</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="posisi" class="col-md-4 col-form-label text-md-end">{{ __('Posisi') }}</label>
                        <div class="col-md-6">
                            <input id="posisi" type="text" class="form-control @error('posisi') is-invalid @enderror" name="posisi" value="{{ old('posisi') }}">
                             @error('posisi')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="no_telepon" class="col-md-4 col-form-label text-md-end">{{ __('No. Telepon') }}</label>
                        <div class="col-md-6">
                            <input id="no_telepon" type="text" class="form-control @error('no_telepon') is-invalid @enderror" name="no_telepon" value="{{ old('no_telepon') }}">
                             @error('no_telepon')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="alamat" class="col-md-4 col-form-label text-md-end">{{ __('Alamat') }}</label>
                        <div class="col-md-6">
                            <textarea id="alamat" class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                             @error('alamat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>


                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Register') }}
                            </button>
                            <a class="btn btn-link" href="{{ route('login') }}">
                                {{ __('Sudah punya akun? Login') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection