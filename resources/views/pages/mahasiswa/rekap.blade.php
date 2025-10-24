@extends('layouts.app')

@section('content')
@include('layouts.sidebarmhs')

<div class="ms-3 mb-4">
    <h3 class="mb-0 h4 font-weight-bolder">Rekap Presensi</h3>
    <p class="mb-4">
        Lihat rekapitulasi presensi Anda per hari dan filter berdasarkan bulan/tahun.
    </p>
</div>

{{-- === Filter Bulan & Tahun === --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="filterForm" method="GET" action="{{ route('mahasiswa.rekap') }}">
                    <div class="row align-items-end">
                        <div class="col-md-3 mb-3">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select name="bulan" id="bulan" class="form-select">
                                <option value="">Semua Bulan</option>
                                @php
                                    $defaultBulan = request('bulan', '');
                                @endphp
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $defaultBulan == $i ? 'selected' : '' }}>
                                        {{ $namabulan[$i] }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select name="tahun" id="tahun" class="form-select">
                                <option value="">Semua Tahun</option>
                                @php
                                    $tahunmulai = 2024;
                                    $tahunskrg = date("Y");
                                    $defaultTahun = request('tahun', '');
                                @endphp
                                @for($tahun = $tahunmulai; $tahun <= $tahunskrg; $tahun++)
                                    <option value="{{ $tahun }}" {{ $defaultTahun == $tahun ? 'selected' : '' }}>
                                        {{ $tahun }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-2"></i> Filter
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button type="button" onclick="resetFilter()" class="btn btn-secondary w-100">
                                <i class="fas fa-undo me-2"></i> Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- === Tabel Rekap Presensi === --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Rekap Presensi Per Hari</h6>
                    <div>
                        <button onclick="exportToExcel()" class="btn btn-success btn-sm me-2">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </button>
                        <button onclick="printRekap()" class="btn btn-primary btn-sm">
                            <i class="fas fa-print me-1"></i> Cetak
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Hari</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Status</th>
                                <th>Lokasi</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($presensi as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tgl_presensi)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tgl_presensi)->locale('id')->dayName }}</td>
                                <td>
                                    @if($item->jam_in)
                                        <span class="badge bg-success">{{ $item->jam_in }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->jam_out)
                                        <span class="badge bg-info">{{ $item->jam_out }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        // Check if there's izin/sakit for this date
                                        $izinData = DB::table('pengajuan_izin')
                                            ->where('npm', Auth::guard('mahasiswa')->user()->npm)
                                            ->where('tgl_izin', $item->tgl_presensi)
                                            ->first();
                                    @endphp
                                    @if($izinData)
                                        @if($izinData->status == 'i')
                                            <span class="badge bg-warning">Izin</span>
                                        @elseif($izinData->status == 's')
                                            <span class="badge bg-danger">Sakit</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $izinData->status }}</span>
                                        @endif
                                    @elseif($item->jam_in && $item->jam_out)
                                        <span class="badge bg-success">Hadir Lengkap</span>
                                    @elseif($item->jam_in)
                                        <span class="badge bg-primary">Hadir</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Hadir</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->lokasi_in)
                                        <small class="text-muted">{{ $item->lokasi_in }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->catat_harian)
                                        <small>{{ Str::limit($item->catat_harian, 50) }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                    <br>Tidak ada data presensi untuk periode yang dipilih.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($presensi->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $presensi->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- === Script untuk Filter & Export === --}}
<script>
function resetFilter() {
    document.getElementById('bulan').value = '';
    document.getElementById('tahun').value = '';
    document.getElementById('filterForm').submit();
}

function exportToExcel() {
    const bulan = document.getElementById('bulan').value;
    const tahun = document.getElementById('tahun').value;
    let url = '{{ route("mahasiswa.rekap") }}' + '?export=excel';
    if (bulan) url += '&bulan=' + bulan;
    if (tahun) url += '&tahun=' + tahun;
    window.open(url, '_blank');
}

function printRekap() {
    const bulan = document.getElementById('bulan').value;
    const tahun = document.getElementById('tahun').value;
    let url = '{{ route("mahasiswa.rekap") }}' + '?print=true';
    if (bulan) url += '&bulan=' + bulan;
    if (tahun) url += '&tahun=' + tahun;
    window.open(url, '_blank');
}
</script>
@endsection
