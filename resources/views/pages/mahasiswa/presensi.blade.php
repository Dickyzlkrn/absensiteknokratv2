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
                            <td>{{ $presensihariini->jam_in ?? '-' }}</td>
                            <td>
                                @if($presensihariini->foto_in)
                                    <img src="{{ asset('storage/uploads/presensi/' . $presensihariini->foto_in) }}"
                                         alt="Foto Masuk"
                                         class="img-thumbnail"
                                         style="width: 80px; height: 60px; object-fit: cover; cursor: pointer;"
                                         onclick="showImageModal('{{ asset('storage/uploads/presensi/' . $presensihariini->foto_in) }}', 'Foto Absen Masuk')">
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $presensihariini->jam_out ?? '-' }}</td>
                            <td>
                                @if($presensihariini->foto_out)
                                    <img src="{{ asset('storage/uploads/presensi/' . $presensihariini->foto_out) }}"
                                         alt="Foto Keluar"
                                         class="img-thumbnail"
                                         style="width: 80px; height: 60px; object-fit: cover; cursor: pointer;"
                                         onclick="showImageModal('{{ asset('storage/uploads/presensi/' . $presensihariini->foto_out) }}', 'Foto Absen Keluar')">
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
    box-shadow: 0 8px 15px rgba(0,0,0,0.2);
}
.presensi-btn .icon {
    width: 60px;
    height: 60px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.notification {
    animation: slideIn 0.3s ease forwards, slideOut 0.3s ease 4.7s forwards;
}
@keyframes slideIn {
    from {opacity:0; transform: translateX(100%);}
    to {opacity:1; transform: translateX(0);}
}
@keyframes slideOut {
    from {opacity:1; transform: translateX(0);}
    to {opacity:0; transform: translateX(100%);}
}
</style>

{{-- Script Presensi --}}
<script>
// Presensi Masuk dan Pulang
const btnPresensiMasuk = document.getElementById('btn-presensi-masuk');
const btnPresensiPulang = document.getElementById('btn-presensi-pulang');
const cameraInput = document.getElementById('cameraInput');

let isPulang = false;

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
        const base64Image = e.target.result;
        let catat_harian = '';
        if (isPulang) {
            catat_harian = prompt('Masukkan deskripsi aktivitas hari ini:');
            if (catat_harian === null) return; // Cancelled
        }
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                kirimPresensi(base64Image, pos.coords.latitude, pos.coords.longitude, catat_harian);
            }, () => kirimPresensi(base64Image, '', '', catat_harian));
        } else {
            kirimPresensi(base64Image, '', '', catat_harian);
        }
    };
    reader.readAsDataURL(file);
});

function kirimPresensi(image, lat, long, catat_harian) {
    fetch("{{ route('mahasiswa.storepresensi') }}", {
        method: "POST",
        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
        body: JSON.stringify({ image: image, lokasi: lat && long ? lat+','+long : '', catat_harian: catat_harian })
    })
    .then(res => res.json())
    .then(res => {
        if(res.status === 'success'){
            showNotification(res.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showNotification(res.message, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        showNotification('Terjadi kesalahan saat mengirim data', 'error');
    });
}

function showNotification(message, type) {
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());

    const notification = document.createElement('div');
    notification.className = `notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
    notification.innerHTML = `
        <div class="flex items-center gap-2">
            <i class="material-symbols-rounded">${type === 'success' ? 'check_circle' : 'error'}</i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Fungsi untuk menampilkan modal gambar
function showImageModal(imageSrc, title) {
    document.getElementById('imageModalLabel').textContent = title;
    document.getElementById('modalImage').src = imageSrc;
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}
</script>
@endsection
