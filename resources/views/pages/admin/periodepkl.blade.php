@extends('layouts.app')
@section('title', 'Periode PKL | Website Absensi V2 - Universitas Teknokrat Indonesia')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold mb-1 text-dark">📅 Data Periode PKL</h4>
            <p class="text-sm text-muted">Kelola daftar periode PKL/Magang Universitas Teknokrat Indonesia</p>
        </div>
    </div>

    {{-- Tombol Tambah --}}
    <div class="mb-3">
        <button type="button" class="btn bg-gradient-danger text-white shadow-sm" data-bs-toggle="modal"
            data-bs-target="#modalPeriode" id="btnTambahPeriode">
            <i class="material-symbols-rounded me-1">add_circle</i> Tambah Periode
        </button>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h6 class="text-uppercase text-sm fw-bold mb-0">Daftar Periode PKL</h6>
        </div>
        <div class="card-body px-0 pt-2 pb-3">
            <div class="table-responsive">
                <table class="table table-hover align-items-center mb-0">
                    <thead class="bg-light">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Nama Periode</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($periodes as $periode)
                            <tr class="text-center align-middle">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $periode->nama_periode }}</td>
                                <td>{{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($periode->tanggal_selesai)->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-warning edit"
                                            data-id="{{ $periode->id }}"
                                            data-nama="{{ $periode->nama_periode }}"
                                            data-mulai="{{ $periode->tanggal_mulai }}"
                                            data-selesai="{{ $periode->tanggal_selesai }}">
                                            <i class="material-symbols-rounded">edit</i>
                                        </button>

                                        <form method="POST" action="{{ route('admin.periodepkl.destroy', $periode->id) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus periode ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="material-symbols-rounded">delete</i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Belum ada data periode.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Modal Tambah/Edit Periode --}}
<div class="modal fade" id="modalPeriode" tabindex="-1" aria-labelledby="modalPeriodeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content shadow-lg border-0 rounded-3" id="formPeriode" method="POST"
            action="{{ route('admin.periodepkl.store') }}">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="modal-header bg-gradient-danger text-white">
                <h5 class="modal-title" id="modalPeriodeLabel">
                    <i class="material-symbols-rounded me-1">event</i> Tambah Periode
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body form-section">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Nama Periode <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_periode" id="nama_periode"
                            placeholder="Contoh: Periode Ganjil 2025/2026" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal_selesai" id="tanggal_selesai" required>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="material-symbols-rounded me-1">close</i> Tutup
                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="material-symbols-rounded me-1">save</i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- === Style Input & Placeholder === --}}
<style>
    .form-control,
    .form-select {
        border: 1.5px solid #d1d5db !important;
        border-radius: 8px !important;
        padding: 10px 12px !important;
        background-color: #fff !important;
        font-size: 14px !important;
        color: #374151 !important;
    }

    .form-control::placeholder {
        color: #9ca3af !important;
        font-style: italic;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #dc2626 !important; /* warna merah sesuai tema */
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15) !important;
        outline: none !important;
    }
</style>

{{-- === Script Modal & Reset === --}}
<script>
    const btnTambah = document.getElementById('btnTambahPeriode');
    const form = document.getElementById('formPeriode');
    const methodInput = document.getElementById('formMethod');

    // Mode tambah baru
    btnTambah.addEventListener('click', () => {
        form.action = "{{ route('admin.periodepkl.store') }}";
        methodInput.value = 'POST';
        form.reset();
        document.getElementById('modalPeriodeLabel').innerHTML =
            '<i class="material-symbols-rounded me-1">event</i> Tambah Periode';
    });

    // Mode edit
    document.querySelectorAll('.edit').forEach(function(btn) {
        btn.addEventListener('click', function() {
            form.action = `/admin/periodepkl/${this.dataset.id}`;
            methodInput.value = 'PUT';
            document.getElementById('nama_periode').value = this.dataset.nama;
            document.getElementById('tanggal_mulai').value = this.dataset.mulai;
            document.getElementById('tanggal_selesai').value = this.dataset.selesai;
            document.getElementById('modalPeriodeLabel').innerHTML =
                '<i class="material-symbols-rounded me-1">edit</i> Edit Periode';
            new bootstrap.Modal(document.getElementById('modalPeriode')).show();
        });
    });
</script>

@endsection
