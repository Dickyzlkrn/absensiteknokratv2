@extends('layouts.app')
@section('title', 'Rekap Presensi | Website Absensi V2 - Universitas Teknokrat Indonesia')

@section('content')
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="fw-bold mb-1 text-dark">📊 Rekap Presensi</h4>
                <p class="text-sm text-muted">Cetak atau ekspor rekap presensi seluruh mahasiswa PKL/Magang</p>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Mahasiswa</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $totalMahasiswa }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                    <i class="material-symbols-rounded opacity-10" aria-hidden="true">school</i>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Presensi Bulan Ini</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $totalPresensi }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Rata-rata Kehadiran</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $avgAttendanceRate }}%</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                    <i class="material-symbols-rounded opacity-10" aria-hidden="true">trending_up</i>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Bulan Aktif</p>
                                    <h5 class="font-weight-bolder mb-0">{{ date('F Y') }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                    <i class="material-symbols-rounded opacity-10" aria-hidden="true">calendar_today</i>
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
                        <h6>Trend Presensi 6 Bulan Terakhir</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="monthlyChart" class="chart-canvas" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Mahasiswa Teraktif Bulan Ini</h6>
                    </div>
                    <div class="card-body p-3">
                        @if($topStudents->count() > 0)
                            @foreach($topStudents as $student)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="text-center me-3">
                                        <i class="material-symbols-rounded text-success">person</i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-sm">{{ $student->nama_mhs }}</h6>
                                        <p class="text-xs text-muted mb-0">{{ $student->total_presensi }} kali presensi</p>
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

        {{-- Form Filter Rekap --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form action="{{ route('admin.presensi.cetakrekap') }}" method="POST" target="_blank" class="row g-3 align-items-end">
                    @csrf
                    <div class="col-md-6">
                        <label for="bulan" class="form-label fw-semibold">Bulan</label>
                        <select name="bulan" id="bulan" class="form-select" required>
                            <option value="">-- Pilih Bulan --</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }}>
                                    {{ $namabulan[$i] }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="tahun" class="form-label fw-semibold">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select" required>
                            <option value="">-- Pilih Tahun --</option>
                            @php
                                $tahunmulai = 2024;
                                $tahunskrg = date('Y');
                            @endphp
                            @for ($tahun = $tahunmulai; $tahun <= $tahunskrg; $tahun++)
                                <option value="{{ $tahun }}" {{ date('Y') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                        <button type="submit"
                            class="btn bg-gradient-primary text-white d-flex align-items-center justify-content-center px-4">
                            <i class="material-symbols-rounded me-1">print</i> Cetak Rekap
                        </button>

                        <button type="submit" name="exportexcel"
                            class="btn bg-gradient-success text-white d-flex align-items-center justify-content-center px-4">
                            <i class="material-symbols-rounded me-1">file_download</i> Export Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Keterangan Info --}}
        <div class="alert alert-info border-0 shadow-sm mb-0">
            <i class="material-symbols-rounded align-middle me-1">info</i>
            Pilih bulan dan tahun untuk mencetak atau mengekspor rekap presensi seluruh mahasiswa.
        </div>
    </div>

    {{-- Style tambahan agar seragam dengan halaman Data Mahasiswa --}}
    <style>
        .form-select {
            border: 1.5px solid #d1d5db !important;
            border-radius: 8px !important;
            padding: 10px 12px !important;
            background-color: #fff !important;
            font-size: 14px !important;
            color: #374151 !important;
        }

        .form-select:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
            outline: none !important;
        }

        .btn {
            border-radius: 8px !important;
            font-weight: 500 !important;
        }
    </style>

    {{-- Chart.js Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Monthly Trend Chart
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            const monthlyData = @json($monthlyData);

            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: monthlyData.map(item => item.month),
                    datasets: [{
                        label: 'Total Presensi',
                        data: monthlyData.map(item => item.count),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        tension: 0.4,
                        fill: true
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
        });
    </script>
@endsection
