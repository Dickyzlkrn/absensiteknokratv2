@extends('layouts.app')

@section('title', 'Dashboard | Absensi V2 - Universitas Teknokrat Indonesia')

@section('content')
<div class="ms-3 mb-4">
    <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
    <p class="mb-4">
        Selamat datang di <strong>Website Absensi V2 Mahasiswa Universitas Teknokrat Indonesia</strong>.<br>
        Pantau dan kelola aktivitas absensi mahasiswa PKL/Magang dengan mudah dan efisien.
    </p>
</div>

{{-- === Statistik Utama === --}}
<div class="row">
    {{-- Mahasiswa Hadir Hari Ini --}}
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3 d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-sm mb-0 text-capitalize">Mahasiswa Hadir Hari Ini</p>
                    <h4 class="mb-0">{{ $hadirHariIni }}</h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark text-center border-radius-lg">
                    <i class="material-symbols-rounded opacity-10">check_circle</i>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3 text-sm">
                Data kehadiran {{ date('d M Y') }}
            </div>
        </div>
    </div>

    {{-- Jumlah Mahasiswa --}}
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3 d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-sm mb-0 text-capitalize">Jumlah Mahasiswa</p>
                    <h4 class="mb-0">{{ $totalMahasiswa }}</h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark text-center border-radius-lg">
                    <i class="material-symbols-rounded opacity-10">school</i>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3 text-sm">
                Total mahasiswa aktif PKL/Magang
            </div>
        </div>
    </div>

    {{-- Izin --}}
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3 d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-sm mb-0 text-capitalize">Mahasiswa Izin</p>
                    <h4 class="mb-0">{{ $rekapizin->jmlizin ?? 0 }}</h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark text-center border-radius-lg">
                    <i class="material-symbols-rounded opacity-10">event_busy</i>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3 text-sm">
                Jumlah izin bulan ini
            </div>
        </div>
    </div>

    {{-- Sakit --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-header p-2 ps-3 d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-sm mb-0 text-capitalize">Mahasiswa Sakit</p>
                    <h4 class="mb-0">{{ $rekapizin->jmlsakit ?? 0 }}</h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark text-center border-radius-lg">
                    <i class="material-symbols-rounded opacity-10">medical_information</i>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3 text-sm">
                Jumlah sakit bulan ini
            </div>
        </div>
    </div>
</div>

{{-- === Grafik Kehadiran & Izin/Sakit (2 grafik berdampingan) === --}}
<div class="row mt-4">
    {{-- Grafik Kehadiran --}}
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h6>Grafik Kehadiran Per Bulan</h6>
                <p class="text-sm">Jumlah mahasiswa hadir per bulan</p>
                <canvas id="chart-hadir" class="chart-canvas" height="150"></canvas>
            </div>
        </div>
    </div>

    {{-- Grafik Izin & Sakit --}}
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h6>Grafik Izin & Sakit Per Bulan</h6>
                <p class="text-sm">Perbandingan jumlah izin dan sakit per bulan</p>
                <canvas id="chart-izin" class="chart-canvas" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- === Tabel Data Absensi Hari Ini === --}}
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header pb-0">
                <h6>Data Absensi Mahasiswa Hari Ini</h6>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Mahasiswa</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NPM</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($absensiHariIni as $a)
                            <tr>
                                <td class="ps-3">{{ $a->nama_mhs }}</td>
                                <td>{{ $a->npm }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($a->tgl_presensi)->translatedFormat('d F Y') }}</td>
                                <td class="text-center">
                                    @if ($a->jam_in)
                                        <span class="text-success">Hadir</span>
                                    @else
                                        <span class="text-danger">Belum Absen</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data absensi hari ini</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- === Grafik Script === --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = {!! json_encode($labels) !!};
const dataHadir = {!! json_encode($dataJumlah) !!};

// === Grafik Kehadiran Per Bulan ===
new Chart(document.getElementById('chart-hadir'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Mahasiswa Hadir',
            data: dataHadir,
            backgroundColor: '#4CAF50',
            borderColor: '#2E7D32',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// === Grafik Izin & Sakit === (dummy sementara, ambil dari $rekapizin kalau mau per bulan)
new Chart(document.getElementById('chart-izin'), {
    type: 'bar',
    data: {
        labels: ['Bulan Ini'],
        datasets: [
            {
                label: 'Izin',
                data: [{{ $rekapizin->jmlizin ?? 0 }}],
                backgroundColor: '#FFC107'
            },
            {
                label: 'Sakit',
                data: [{{ $rekapizin->jmlsakit ?? 0 }}],
                backgroundColor: '#F44336'
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endsection
