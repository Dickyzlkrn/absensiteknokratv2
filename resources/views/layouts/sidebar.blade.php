<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand px-4 py-3 m-0" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('assets/img/logo.png') }}" class="navbar-brand-img" width="26" height="26" alt="main_logo">
            <span class="ms-1 text-sm text-dark fw-bold">Website Absensi V2</span><br>
            <span class="ms-1 text-xs text-muted">Universitas Teknokrat Indonesia</span>
        </a>
    </div>

    <hr class="horizontal dark mt-0 mb-2">

    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">

            {{-- DASHBOARD --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="material-symbols-rounded opacity-5">dashboard</i>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            {{-- DATA MASTER --}}
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">
                    Data Master
                </h6>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.mahasiswa.index') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route('admin.mahasiswa.index') }}">
                    <i class="material-symbols-rounded opacity-5">school</i>
                    <span class="nav-link-text ms-1">Daftar Mahasiswa</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.mpmahasiswa') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route('admin.mpmahasiswa') }}">
                    <i class="material-symbols-rounded opacity-5">map</i>
                    <span class="nav-link-text ms-1">Maps & Foto Mahasiswa</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.periodepkl') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route('admin.periodepkl') }}">
                    <i class="material-symbols-rounded opacity-5">calendar_month</i>
                    <span class="nav-link-text ms-1">Periode PKL</span>
                </a>
            </li>

            {{-- LAPORAN --}}
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">
                    Laporan Presensi
                </h6>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.presensi.laporan') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route('admin.presensi.laporan') }}">
                    <i class="material-symbols-rounded opacity-5">fact_check</i>
                    <span class="nav-link-text ms-1">Laporan Presensi</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.presensi.rekap') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route('admin.presensi.rekap') }}">
                    <i class="material-symbols-rounded opacity-5">summarize</i>
                    <span class="nav-link-text ms-1">Rekap Presensi</span>
                </a>
            </li>

            {{-- HALAMAN AKUN --}}
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">
                    Halaman Akun
                </h6>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.settingakun') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route('admin.settingakun') }}">
                    <i class="material-symbols-rounded opacity-5">manage_accounts</i>
                    <span class="nav-link-text ms-1">Setting Akun</span>
                </a>
            </li>

            <li class="nav-item">
                <form action="{{ route('logoutadmin') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="nav-link text-danger w-100 border-0 bg-transparent text-start">
                        <i class="material-symbols-rounded opacity-5">logout</i>
                        <span class="nav-link-text ms-1">Logout</span>
                    </button>
                </form>
            </li>

        </ul>
    </div>

    <div class="sidenav-footer position-absolute w-100 bottom-0">
        <div class="mx-3 text-center">
            <p class="text-xs text-muted mb-2">Sistem Absensi PKL/Magang</p>
            <a class="btn bg-gradient-dark w-100" href="https://teknokrat.ac.id" target="_blank" type="button">
                Universitas Teknokrat Indonesia
            </a>
        </div>
    </div>
</aside>
