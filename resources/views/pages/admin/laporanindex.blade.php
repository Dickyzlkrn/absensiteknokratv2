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

        {{-- Card Form --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form action="/presensi/cetaklaporan" target="_blank" method="POST">
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
