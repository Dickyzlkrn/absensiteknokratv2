@extends('layouts.app')
@section('content')
@include('layouts.sidebarmhs')

<div class="ms-3 mb-4">
    <h3 class="mb-0 h4 font-weight-bolder">Presensi</h3>
    <p class="text-muted mb-4">
        Lakukan absensi masuk dan pulang dengan mudah.
    </p>
</div>

{{-- Tombol Presensi --}}
<div class="row mb-5">
    <div class="col-12 d-flex justify-content-center gap-4 flex-wrap">
        <button id="btn-presensi-masuk" class="btn btn-success presensi-btn">
            <div class="icon bg-success text-white shadow rounded-circle mb-2">
                <i class="material-symbols-rounded fs-2">login</i>
            </div>
            <h6 class="mb-0">Absen Masuk</h6>
            <small class="text-muted">Absen masuk dengan foto</small>
        </button>

        <button id="btn-presensi-pulang" class="btn btn-danger presensi-btn">
            <div class="icon bg-danger text-white shadow rounded-circle mb-2">
                <i class="material-symbols-rounded fs-2">logout</i>
            </div>
            <h6 class="mb-0">Absen Pulang</h6>
            <small class="text-muted">Absen pulang dengan deskripsi</small>
        </button>

        <button id="btn-pengajuan-izin" class="btn btn-warning presensi-btn" data-bs-toggle="modal" data-bs-target="#izinModal">
            <div class="icon bg-warning text-white shadow rounded-circle mb-2">
                <i class="material-symbols-rounded fs-2">event_note</i>
            </div>
            <h6 class="mb-0">Pengajuan Izin</h6>
            <small class="text-muted">Ajukan izin dengan deskripsi</small>
        </button>
    </div>
</div>

{{-- Input Kamera Tersembunyi --}}
<input type="file" accept="image/*" capture="environment" id="cameraInput" style="display:none">

{{-- Tabel Absensi Hari Ini --}}
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">Absensi Hari Ini</h6>
            </div>
            <div class="card-body table-responsive">
                @if($presensihariini)
                <table class="table table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Jam Masuk</th>
                            <th>Foto Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Foto Keluar</th>
                            <th>Catatan Harian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $presensihariini->jam_in == '00:00:00' ? 'Izin' : ($presensihariini->jam_in ?? '-') }}</td>
                            <td>
                                @if($presensihariini->foto_in && $presensihariini->foto_in != 'izin_auto.png')
                                <img src="{{ Storage::url('uploads/presensi/' . $presensihariini->foto_in) }}"
                                    alt="Foto Masuk"
                                    class="img-thumbnail"
                                    style="width: 80px; height: 60px; object-fit: cover; cursor: pointer;"
                                    onclick="showImageModal('{{ Storage::url('uploads/presensi/' . $presensihariini->foto_in) }}', 'Foto Absen Masuk')">
                                @elseif($presensihariini->foto_in == 'izin_auto.png')
                                <span class="badge bg-info">Izin</span>
                                @else
                                -
                                @endif
                            </td>
                            <td>{{ $presensihariini->jam_out ?? '-' }}</td>
                            <td>
                                @if($presensihariini->foto_out)
                                <img src="{{ Storage::url('uploads/presensi/' . $presensihariini->foto_out) }}"
                                    alt="Foto Keluar"
                                    class="img-thumbnail"
                                    style="width: 80px; height: 60px; object-fit: cover; cursor: pointer;"
                                    onclick="showImageModal('{{ Storage::url('uploads/presensi/' . $presensihariini->foto_out) }}', 'Foto Absen Keluar')">
                                @else
                                -
                                @endif
                            </td>
                            <td>{{ $presensihariini->catat_harian ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
                @else
                <p class="text-center text-muted">Belum ada absensi hari ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Tabel Pengajuan Izin --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">Pengajuan Izin</h6>
            </div>
            <div class="card-body table-responsive">
                @if($dataizin->count() > 0)
                <table class="table table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal Izin</th>
                            <th>Jenis Izin</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataizin as $izin)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($izin->tgl_izin)->format('d/m/Y') }}</td>
                            <td>
                                @if($izin->status == 'i')
                                <span class="badge bg-warning">Izin</span>
                                @else
                                <span class="badge bg-danger">Sakit</span>
                                @endif
                            </td>
                            <td>{{ $izin->deskripsi_izin }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-center text-muted">Belum ada pengajuan izin.</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal untuk menampilkan gambar --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Foto Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Foto Absensi" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

{{-- Modal untuk Pengajuan Izin --}}
<div class="modal fade" id="izinModal" tabindex="-1" aria-labelledby="izinModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="izinModalLabel">Pengajuan Izin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="izinForm">
                    @csrf
                    <div class="mb-3">
                        <label for="tgl_izin" class="form-label">Tanggal Izin</label>
                        <input type="date" class="form-control" id="tgl_izin" name="tgl_izin" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Jenis Izin</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="">Pilih Jenis Izin</option>
                            <option value="i">Izin</option>
                            <option value="s">Sakit</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi_izin" class="form-label">Deskripsi Izin</label>
                        <textarea class="form-control" id="deskripsi_izin" name="deskripsi_izin" rows="4" placeholder="Jelaskan alasan izin secara detail..." required></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Ajukan Izin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Style tambahan --}}
<style>
    .presensi-btn {
        width: 180px;
        padding: 1.5rem 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .presensi-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }

    .presensi-btn .icon {
        width: 60px;
        height: 60px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .presensi-btn h6 {
        color: white;
    }

    .presensi-btn small {
        color: white;
    }

    .notification {
        animation: slideIn 0.3s ease forwards, slideOut 0.3s ease 4.7s forwards;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(100%);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }

        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }

    /* ===== Popup Card Style ===== */
    .overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        display: none;
    }

    .popup-card {
        background: #fff;
        width: 90%;
        max-width: 480px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        animation: fadeIn .25s ease;
        overflow: hidden;
    }

    .popup-header {
        background: #f8f9fa;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #dee2e6;
    }

    .popup-body {
        padding: 1rem 1.25rem;
    }

    .popup-footer {
        padding: 0.75rem 1.25rem;
        border-top: 1px solid #dee2e6;
    }

    @media (max-width: 576px) {
        .popup-card {
            width: 95%;
            margin: 0 1rem;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>

{{-- Script Presensi --}}
<script>
    let capturedImage = '';
    let isPulang = false;
    let pendingLat = '',
        pendingLong = '';

    const btnPresensiMasuk = document.getElementById('btn-presensi-masuk');
    const btnPresensiPulang = document.getElementById('btn-presensi-pulang');
    const cameraInput = document.getElementById('cameraInput');

    btnPresensiMasuk.addEventListener('click', () => {
        isPulang = false;
        cameraInput.click();
    });

    btnPresensiPulang.addEventListener('click', () => {
        isPulang = true;
        cameraInput.click();
    });

    cameraInput.addEventListener('change', () => {
        const file = cameraInput.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            capturedImage = e.target.result;
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    pendingLat = pos.coords.latitude;
                    pendingLong = pos.coords.longitude;
                    if (isPulang) {
                        openPopup(); // ===> tampilkan popup deskripsi
                    } else {
                        kirimPresensi(capturedImage, pendingLat, pendingLong, '', 'in');
                    }
                }, () => {
                    pendingLat = '';
                    pendingLong = '';
                    if (isPulang) {
                        openPopup();
                    } else {
                        kirimPresensi(capturedImage, '', '', '', 'in');
                    }
                });
            } else {
                if (isPulang) openPopup();
                else kirimPresensi(capturedImage, '', '', '', 'in');
            }
        };
        reader.readAsDataURL(file);
    });

    function kirimPresensi(image, lat, long, catat_harian, type) {
        fetch("{{ route('mahasiswa.storepresensi') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    image: image,
                    lokasi: lat && long ? lat + ',' + long : '',
                    catat_harian: catat_harian,
                    type: type
                })
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    showNotification(res.message, 'success');
                    setTimeout(() => location.reload(), 2000);
                } else showNotification(res.message, 'error');
            })
            .catch(err => {
                console.error(err);
                showNotification('Terjadi kesalahan saat mengirim data', 'error');
            });
    }

    function showNotification(message, type) {
        const existing = document.querySelectorAll('.notification');
        existing.forEach(n => n.remove());
        const n = document.createElement('div');
        n.className = `notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
        n.innerHTML = `<div class="flex items-center gap-2"><i class="material-symbols-rounded">${type === 'success' ? 'check_circle' : 'error'}</i><span>${message}</span></div>`;
        document.body.appendChild(n);
        setTimeout(() => n.remove(), 5000);
    }

    function showImageModal(imageSrc, title) {
        document.getElementById('imageModalLabel').textContent = title;
        document.getElementById('modalImage').src = imageSrc;
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    }

    // === POPUP CARD ===
    const overlay = document.createElement('div');
    overlay.id = "popupOverlay";
    overlay.className = "overlay";
    overlay.innerHTML = `
  <div class="popup-card">
    <div class="popup-header d-flex justify-content-between align-items-center">
      <h6 class="mb-0">Deskripsi Aktivitas Hari Ini</h6>
      <button type="button" class="btn-close" aria-label="Tutup" id="closePopup"></button>
    </div>
    <div class="popup-body">
      <textarea id="descInput" class="form-control" rows="4" placeholder="Tuliskan deskripsi kegiatan Anda hari ini..." required></textarea>
    </div>
    <div class="popup-footer text-end">
      <button class="btn btn-secondary" id="cancelPopup">Batal</button>
      <button class="btn btn-primary" id="savePopup">Simpan</button>
    </div>
  </div>
`;
    document.body.appendChild(overlay);

    const closePopupBtn = document.getElementById('closePopup');
    const cancelPopup = document.getElementById('cancelPopup');
    const savePopup = document.getElementById('savePopup');
    const descInput = document.getElementById('descInput');

    function openPopup() {
        overlay.style.display = 'flex';
        descInput.value = '';
        descInput.focus();
        document.body.style.overflow = 'hidden';
    }

    function closePopup() {
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    }

    closePopupBtn.addEventListener('click', closePopup);
    cancelPopup.addEventListener('click', closePopup);
    savePopup.addEventListener('click', () => {
        const desc = descInput.value.trim();
        if (!desc) {
            alert('Tuliskan deskripsi terlebih dahulu.');
            return;
        }
        closePopup();
        kirimPresensi(capturedImage, pendingLat, pendingLong, desc, 'out');
    });

    // Fungsi pengajuan izin (tetap)
    document.getElementById('izinForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = {
            tgl_izin: formData.get('tgl_izin'),
            status: formData.get('status'),
            deskripsi_izin: formData.get('deskripsi_izin'),
            _token: formData.get('_token')
        };

        fetch("{{ route('mahasiswa.storeizin') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": data._token
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    showNotification(res.message, 'success');
                    document.getElementById('izinForm').reset();

                    // ✅ Tutup modal izin dengan paksa agar 100% tertutup
                    const izinModalEl = document.getElementById('izinModal');

                    // Tutup instance modal Bootstrap jika ada
                    try {
                        const izinModalInstance = bootstrap.Modal.getInstance(izinModalEl) || new bootstrap.Modal(izinModalEl);
                        izinModalInstance.hide();
                    } catch (e) {
                        console.warn('Bootstrap modal instance not found, force close manually.');
                    }

                    // Hapus kelas & backdrop manual untuk memastikan tertutup
                    izinModalEl.classList.remove('show');
                    izinModalEl.style.display = 'none';
                    izinModalEl.setAttribute('aria-hidden', 'true');
                    izinModalEl.removeAttribute('aria-modal');
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';

                    const modalBackdrop = document.querySelector('.modal-backdrop');
                    if (modalBackdrop) modalBackdrop.remove();

                    // Redirect setelah modal tertutup
                    setTimeout(() => {
                        window.location.href = '{{ route("mahasiswa.presensi") }}';
                    }, 100);


                    // Immediate redirect instead of reload after timeout
                    window.location.href = '{{ route("mahasiswa.presensi") }}';
                } else {
                    showNotification(res.message, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showNotification('Terjadi kesalahan saat mengirim pengajuan izin', 'error');
            });
    });
</script>
@endsection