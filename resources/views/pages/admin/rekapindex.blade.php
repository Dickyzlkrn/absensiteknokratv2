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

        {{-- Form Filter Rekap --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form action="/presensi/cetakrekap" method="POST" target="_blank" class="row g-3 align-items-end">
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
@endsection
