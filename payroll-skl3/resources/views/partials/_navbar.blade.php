<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ Auth::check() ? (Auth::user()->role == 'admin' ? route('admin.dashboard') : route('karyawan.dashboard')) : url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                @auth
                    @if(Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.karyawan.index') }}">Kelola Karyawan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.absensi.rekap') }}">Rekap Absensi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.gaji.index') }}">Kelola Gaji</a>
                        </li>
                    @elseif(Auth::user()->role == 'karyawan')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('karyawan.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('karyawan.riwayat.absensi') }}">Riwayat Absensi</a>
                        </li>
                    @endif
                @endauth
            </ul>

            <!-- Right Side Of Navbar -->
            {{-- ... bagian kiri navbar ... --}}
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ms-auto">
            @guest
                @if (Route::has('login'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                @endif

                {{-- TAMBAHKAN INI --}}
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
                {{-- AKHIR TAMBAHAN --}}
            @else
                {{-- ... dropdown user yang sudah login ... --}}
            @endguest
        </ul>
    </div>
</div>
</nav>