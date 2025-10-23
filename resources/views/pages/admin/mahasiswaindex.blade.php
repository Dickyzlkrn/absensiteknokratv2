@extends('layouts.app')
@section('title', 'Data Mahasiswa | Website Absensi V2 - Universitas Teknokrat Indonesia')

@section('content')
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="fw-bold mb-1 text-dark">📚 Data Mahasiswa</h4>
                <p class="text-sm text-muted">Kelola data mahasiswa PKL/Magang Universitas Teknokrat Indonesia</p>
            </div>
        </div>

        {{-- Filter + Import --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body pb-2">
                <form class="row g-2 align-items-end" method="GET" action="{{ route('admin.mahasiswa.index') }}">
                    <div class="col-md-3">
                        <label class="form-label">Cari Nama Mahasiswa</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="material-symbols-rounded">search</i></span>
                            <input type="text" class="form-control" name="nama_mhs" placeholder="Ketik nama mahasiswa..."
                                value="{{ request('nama_mhs') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Pilih Program Studi</label>
                        <select class="form-select" name="prodi">
                            <option value="">-- Semua Prodi --</option>
                            @foreach ($prodi as $p)
                                <option value="{{ $p }}" {{ request('prodi') == $p ? 'selected' : '' }}>
                                    {{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Pilih Kategori</label>
                        <select class="form-select" name="kategori">
                            <option value="">-- Semua Kategori --</option>
                            <option value="PKL" {{ request('kategori') == 'PKL' ? 'selected' : '' }}>PKL</option>
                            <option value="Magang" {{ request('kategori') == 'Magang' ? 'selected' : '' }}>Magang</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn bg-gradient-success text-white">
                            <i class="material-symbols-rounded me-1">search</i> Cari
                        </button>
                    </div>

                    <div class="col-md-1 text-end">
                        <button type="button" class="btn bg-gradient-dark text-white w-100" data-bs-toggle="modal"
                            data-bs-target="#modalMahasiswa" id="btnTambahmahasiswa">
                            <i class="material-symbols-rounded">add_circle</i>
                        </button>
                    </div>
                </form>

                {{-- Import Excel & Download Format --}}
                <div class="mt-3 d-flex gap-2">
                    <button type="button" class="btn btn-outline-success btn-sm" id="downloadFormatBtn">
                        <i class="material-symbols-rounded me-1">download</i> Unduh Format Import
                    </button>

                    <form action="{{ route('admin.mahasiswa.import') }}" method="POST"
                        enctype="multipart/form-data" class="d-inline" id="importForm">
                        @csrf
                        <input type="file" name="file" id="file" class="d-none" accept=".xlsx, .xls" required>
                        <button type="button" class="btn bg-gradient-primary btn-sm" id="importBtn">
                            <i class="material-symbols-rounded me-1">upload_file</i> Import Excel
                        </button>
                    </form>
                </div>
                <small class="text-danger d-block mt-2">
                    *Gunakan format Excel (XLS/XLSX) sesuai template. Sistem akan menyesuaikan variasi penulisan program studi.
                </small>
            </div>
        </div>

        {{-- Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="text-uppercase text-sm fw-bold mb-0">Daftar Mahasiswa</h6>
            </div>
            <div class="card-body px-0 pt-2 pb-3">
                <div class="table-responsive">
                    <table class="table table-hover align-items-center mb-0">
                        <thead class="bg-light">
                            <tr class="text-center">
                                <th>#</th>
                                <th>Foto</th>
                                <th>NPM</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                                <th>Kategori</th>
                                <th>No HP</th>
                                <th>Tempat PKL</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mahasiswa as $d)
                                <tr class="text-center align-middle">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ $d->foto ? asset('assets/img/' . $d->foto) : asset('assets/img/nopoto.png') }}"
                                            class="avatar avatar-sm rounded-circle border" alt="Foto Mahasiswa">
                                    </td>
                                    <td class="fw-semibold">{{ $d->npm }}</td>
                                    <td>{{ $d->nama_mhs }}</td>
                                    <td>{{ $d->prodi }}</td>
                                    <td>{{ $d->kategori }}</td>
                                    <td>
                                        @php
                                            $nohp = $d->nohp_mhs;
                                            $masked = substr($nohp, 0, 4) . ' XXXX ' . substr($nohp, -4);
                                        @endphp
                                        {{ $masked }}
                                    </td>
                                    <td>{{ $d->tempat_pkl ?? '-' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-warning edit"
                                                data-npm="{{ $d->npm }}" data-nama="{{ $d->nama_mhs }}"
                                                data-prodi="{{ $d->prodi }}" data-kategori="{{ $d->kategori }}" data-nohp="{{ $d->nohp_mhs }}"
                                                data-tempat="{{ $d->tempat_pkl }}" data-foto="{{ $d->foto ?? '' }}">
                                                <i class="material-symbols-rounded">edit</i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.mahasiswa.destroy', $d->npm) }}"
                                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="material-symbols-rounded">delete</i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-3">Belum ada data mahasiswa.</td>
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

    {{-- Modal Tambah/Edit Mahasiswa --}}
    <div class="modal fade" id="modalMahasiswa" tabindex="-1" aria-labelledby="modalMahasiswaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content shadow-lg border-0 rounded-3" id="formMahasiswa" method="POST"
                action="{{ route('admin.mahasiswa.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="modal-header bg-gradient-danger text-white">
                    <h5 class="modal-title" id="modalMahasiswaLabel">
                        <i class="material-symbols-rounded me-1">person_add</i> Tambah Mahasiswa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body form-section">
                    <div class="row g-4">
                        {{-- Kolom Kiri --}}
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">NPM <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="npm" id="npm"
                                        placeholder="Masukkan NPM, contoh: 202210301045" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Mahasiswa <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nama_mhs" id="nama_mhs"
                                        placeholder="Masukkan nama lengkap" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Program Studi <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="prodi" id="prodi_input" required>
                                        <option value="">-- Pilih Prodi --</option>
                                        @foreach ($prodi as $p)
                                            <option value="{{ $p }}">{{ $p }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                                    <select class="form-select" name="kategori" id="kategori_input" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="PKL">PKL</option>
                                        <option value="Magang">Magang</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">No HP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nohp_mhs" id="nohp_mhs"
                                        placeholder="08xxxxxxxxxx" required>
                                    <small class="text-muted">Tanpa spasi, contoh: 081234567890</small>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Tempat PKL</label>
                                    <input type="text" class="form-control" name="tempat_pkl" id="tempat_pkl"
                                        placeholder="Contoh: PT. Contoh Indonesia">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Password</label>
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Minimal 6 karakter (kosongkan jika tidak ingin mereset)">
                                    <small class="text-muted">Ketik password untuk melihat kekuatan.</small>
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Kanan (Foto) --}}
                        <div class="col-md-4">
                            <div class="card border rounded-3 p-3 text-center">
                                <label class="form-label fw-semibold mb-2">Foto Mahasiswa</label>
                                <img id="previewFoto" src="{{ asset('assets/img/nopoto.png') }}"
                                    class="photo-preview img-fluid rounded mb-2 border" alt="Preview Foto"
                                    style="width: 100%; height: 200px; object-fit: cover;">
                                <input type="file" name="foto" id="foto" class="d-none"
                                    accept=".jpg,.jpeg,.png">
                                <button type="button" class="btn btn-outline-secondary btn-sm w-100 mb-2"
                                    onclick="document.getElementById('foto').click()">
                                    <i class="material-symbols-rounded me-1">upload</i> Upload Foto
                                </button>
                                <button type="button" id="btnRemovePhoto"
                                    class="btn btn-outline-danger btn-sm w-100 d-none">
                                    <i class="material-symbols-rounded me-1">delete</i> Hapus Foto
                                </button>
                                <div class="text-start mt-3 small text-muted">
                                    <p class="mb-1">📁 Format: JPG/PNG</p>
                                    <p class="mb-1">📦 Maks ukuran: 2MB</p>
                                    <p class="mb-0">📏 Rekomendasi: 400×400 px</p>
                                </div>
                            </div>
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
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
            outline: none !important;
        }

        .photo-preview {
            background-color: #f9fafb;
            border: 2px dashed #d1d5db;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #9ca3af;
            font-style: italic;
        }

        #btnRemovePhoto,
        .btn-outline-secondary {
            border-radius: 6px;
            font-weight: 500;
        }
    </style>

    {{-- === Script Preview Foto & Modal === --}}
    <script>
        const fotoInput = document.getElementById('foto');
        const previewFoto = document.getElementById('previewFoto');
        const btnRemovePhoto = document.getElementById('btnRemovePhoto');
        const importBtn = document.getElementById('importBtn');
        const importForm = document.getElementById('importForm');
        const fileInput = document.getElementById('file');

        // Preview foto
        fotoInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    previewFoto.src = event.target.result;
                    btnRemovePhoto.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        // Hapus foto
        btnRemovePhoto.addEventListener('click', () => {
            previewFoto.src = "{{ asset('assets/img/nopoto.png') }}";
            fotoInput.value = '';
            btnRemovePhoto.classList.add('d-none');
        });

        // Download format button
        document.getElementById('downloadFormatBtn').addEventListener('click', () => {
            window.location.href = "{{ route('admin.mahasiswa.export.format') }}";
        });

        // Import Excel: click file input when button is clicked
        importBtn.addEventListener('click', () => {
            fileInput.click();
        });

        // Submit form when file is selected
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                importForm.submit();
            }
        });

        // Reset form saat tambah baru
        document.getElementById('btnTambahmahasiswa').addEventListener('click', function() {
            const form = document.getElementById('formMahasiswa');
            form.action = "{{ route('admin.mahasiswa.store') }}";
            document.getElementById('formMethod').value = 'POST';
            form.reset();
            previewFoto.src = "{{ asset('assets/img/nopoto.png') }}";
            btnRemovePhoto.classList.add('d-none');
            document.getElementById('modalMahasiswaLabel').innerHTML =
                '<i class="material-symbols-rounded me-1">person_add</i> Tambah Mahasiswa';
        });

        // Mode edit
        document.querySelectorAll('.edit').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const form = document.getElementById('formMahasiswa');
                form.action = `/admin/mahasiswa/${this.dataset.npm}`;
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('npm').value = this.dataset.npm;
                document.getElementById('nama_mhs').value = this.dataset.nama;
                document.getElementById('prodi_input').value = this.dataset.prodi;
                document.getElementById('kategori_input').value = this.dataset.kategori;
                document.getElementById('nohp_mhs').value = this.dataset.nohp;
                document.getElementById('tempat_pkl').value = this.dataset.tempat;
                document.getElementById('modalMahasiswaLabel').innerHTML =
                    '<i class="material-symbols-rounded me-1">edit</i> Edit Mahasiswa';
                new bootstrap.Modal(document.getElementById('modalMahasiswa')).show();
            });
        });
    </script>

@endsection
