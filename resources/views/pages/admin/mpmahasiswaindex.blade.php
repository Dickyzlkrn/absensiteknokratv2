@extends('layouts.app')
@section('title', 'Maps & Foto Mahasiswa | Website Absensi V2 - Universitas Teknokrat Indonesia')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold mb-1 text-dark">🗺️ Maps & Foto Mahasiswa</h4>
            <p class="text-sm text-muted">Lihat lokasi dan foto kehadiran mahasiswa berdasarkan periode dan prodi.</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.mpmahasiswa') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Filter Prodi</label>
                    <select name="prodi_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Semua Prodi --</option>
                        @foreach($allProdi as $prodi)
                            <option value="{{ $prodi->id }}" {{ $selectedProdiId == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->prodi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Filter Periode PKL</label>
                    <select name="periode_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Semua Periode --</option>
                        @foreach($allPeriode as $periode)
                            <option value="{{ $periode->id }}" {{ $selectedPeriodeId == $periode->id ? 'selected' : '' }}>
                                {{ $periode->nama_periode }} ({{ $periode->tanggal_mulai }} - {{ $periode->tanggal_selesai }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h6 class="text-uppercase text-sm fw-bold mb-0">Data Lokasi & Foto Mahasiswa</h6>
        </div>
        <div class="card-body px-0 pt-2 pb-3">
            <div class="table-responsive">
                <table class="table table-hover align-items-center mb-0">
                    <thead class="bg-light text-center">
                        <tr>
                            <th>#</th>
                            <th>NPM</th>
                            <th>Nama</th>
                            <th>Tanggal Presensi</th>
                            <th>Posisi Masuk</th>
                            <th>Posisi Pulang</th>
                            <th>Foto Masuk</th>
                            <th>Foto Pulang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mahasiswa as $index => $mhs)
                            <tr class="text-center align-middle">
                                <td>{{ ($mahasiswa->currentPage() - 1) * $mahasiswa->perPage() + $index + 1 }}</td>
                                <td>{{ $mhs->npm }}</td>
                                <td>{{ $mhs->nama_mhs }}</td>
                                <td>{{ $mhs->tgl_presensi ?? '-' }}</td>
                                <td>
                                    @if($mhs->posisi_masuk)
                                        <a href="https://www.google.com/maps?q={{ $mhs->posisi_masuk }}" target="_blank" class="text-primary text-decoration-underline">
                                            Lihat Maps
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($mhs->posisi_pulang)
                                        <a href="https://www.google.com/maps?q={{ $mhs->posisi_pulang }}" target="_blank" class="text-primary text-decoration-underline">
                                            Lihat Maps
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($mhs->foto_masuk)
                                        <img src="{{ Storage::url('uploads/presensi/'.$mhs->foto_masuk) }}"
                                             alt="Foto Masuk" class="rounded shadow-sm" width="60" height="60" style="object-fit: cover;">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($mhs->foto_pulang)
                                        <img src="{{ Storage::url('uploads/presensi/'.$mhs->foto_pulang) }}"
                                             alt="Foto Pulang" class="rounded shadow-sm" width="60" height="60" style="object-fit: cover;">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">Tidak ada data mahasiswa ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 px-3">
                {{ $mahasiswa->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

{{-- Style tambahan untuk tabel --}}
<style>
    .table th, .table td {
        vertical-align: middle !important;
        font-size: 14px;
    }
</style>
@endsection
