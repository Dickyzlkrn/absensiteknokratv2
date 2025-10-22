@extends('layouts.app')
@section('title', 'Laporan Presensi | Website Absensi V2 - Universitas Teknokrat Indonesia')

@section('content')
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="fw-bold mb-1 text-dark">📋 Laporan Presensi</h4>
                <p class="text-sm text-muted">Cetak dan ekspor laporan presensi mahasiswa PKL/Magang Universitas Teknokrat Indonesia</p>
            </div>
        </div>

        {{-- Dashboard Cards --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Presensi Bulan Ini</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $totalPresensi }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                    <i class="material-symbols-rounded opacity-10" aria-hidden="true">check_circle</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Izin Bulan Ini</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $totalIzin }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                    <i class="material-symbols-rounded opacity-10" aria-hidden="true">sick</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Prodi Teraktif</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $attendanceByProdi->first()->prodi ?? 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i class="material-symbols-rounded opacity-10" aria-hidden="true">school</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Mahasiswa 100% Hadir</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $perfectAttendance->count() }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                    <i class="material-symbols-rounded opacity-10" aria-hidden="true">star</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="row mb-4">
            <div class="col-lg-8 mb-lg-0 mb-4">
                <div class="card z-index-2">
                    <div class="card-header pb-0">
                        <h6>Trend Presensi Harian Bulan Ini</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="dailyChart" class="chart-canvas" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Presensi per Program Studi</h6>
                    </div>
                    <div class="card-body p-3">
                        @if($attendanceByProdi->count() > 0)
                            @foreach($attendanceByProdi as $prodi)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="text-center me-3">
                                        <i class="material-symbols-rounded text-primary">school</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-sm">{{ $prodi->prodi }}</h6>
                                        <p class="text-xs text-muted mb-0">{{ $prodi->total }} presensi</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-muted">Belum ada data presensi bulan ini</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Perfect Attendance Students --}}
        @if($perfectAttendance->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Mahasiswa dengan Kehadiran 100% Bulan Ini</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            @foreach($perfectAttendance as $student)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="text-center me-3">
                                            <i class="material-symbols-rounded text-success" style="font-size: 24px;">star</i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-sm">{{ $student->nama_mhs }}</h6>
                                            <p class="text-xs text-muted mb-0">{{ $student->prodi }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Card Form --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form action="{{ route('admin.presensi.cetaklaporan') }}" target="_blank" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="bulan" class="form-label fw-semibold">Bulan</label>
                            <select name="bulan" id="bulan" class="form-select">
                                <option value="">Pilih Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ date("m") == $i ? 'selected' : '' }}>
                                        {{ $namabulan[$i] }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="tahun" class="form-label fw-semibold">Tahun</label>
                            <select name="tahun" id="tahun" class="form-select">
                                <option value="">Pilih Tahun</option>
                                @php
                                    $tahunmulai = 2024;
                                    $tahunskrg = date("Y");
                                @endphp
                                @for ($tahun = $tahunmulai; $tahun <= $tahunskrg; $tahun++)
                                    <option value="{{ $tahun }}" {{ date("Y") == $tahun ? 'selected' : '' }}>
                                        {{ $tahun }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="npm" class="form-label fw-semibold">Nama Mahasiswa</label>
                            <select name="npm" id="npm" class="form-select">
                                <option value="">Pilih Mahasiswa</option>
                                @foreach ($mahasiswa as $d)
                                    <option value="{{ $d->npm }}">{{ $d->nama_mhs }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6 d-grid">
                            <button type="submit" class="btn bg-gradient-primary text-white">
                                <i class="material-symbols-rounded me-1">print</i> Cetak
                            </button>
                        </div>
                        <div class="col-md-6 d-grid">
                            <button type="submit" name="exportexcel" class="btn bg-gradient-success text-white">
                                <i class="material-symbols-rounded me-1">file_present</i> Export to Excel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- === Style Modern Form === --}}
    <style>
        .form-control, .form-select {
            border: 1.5px solid #d1d5db !important;
            border-radius: 8px !important;
            padding: 10px 12px !important;
            background-color: #fff !important;
            font-size: 14px !important;
            color: #374151 !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
            outline: none !important;
        }

        label.form-label {
            font-size: 14px;
        }
    </style>

    {{-- === Script SweetAlert Validasi === --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Chart.js Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Daily Attendance Chart
            const dailyCtx = document.getElementById('dailyChart').getContext('2d');
            const dailyData = @json($dailyData);

            new Chart(dailyCtx, {
                type: 'bar',
                data: {
                    labels: dailyData.map(item => 'Hari ' + item.day),
                    datasets: [{
                        label: 'Jumlah Presensi',
                        data: dailyData.map(item => item.count),
                        backgroundColor: 'rgba(37, 99, 235, 0.8)',
                        borderColor: '#2563eb',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Form validation
            const form = document.querySelector('form');
            const npmSelect = document.getElementById('npm');

            form.addEventListener('submit', function (e) {
                if (npmSelect.value === "") {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Nama Mahasiswa Belum Dipilih',
                        text: 'Silakan pilih nama mahasiswa terlebih dahulu.'
                    });
                }
            });
        });
    </script>
@endsection
