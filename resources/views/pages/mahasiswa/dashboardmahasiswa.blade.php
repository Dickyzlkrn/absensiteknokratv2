@extends('layouts.app')

@section('content')
@include('layouts.sidebarmhs')

<div class="ms-3 mb-4">
    <h3 class="mb-0 h4 font-weight-bolder">Dashboard Mahasiswa</h3>
    <p class="mb-4">
        Selamat datang, <strong>{{ Auth::guard('mahasiswa')->user()->nama_mhs }}</strong>! <br>
        Pantau kehadiran dan aktivitas absensi PKL/Magang Anda dengan mudah dan efisien.
    </p>
</div>

{{-- === Profil Mahasiswa === --}}
<div class="row mb-4">
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center">
                @if(Auth::guard('mahasiswa')->user()->foto)
                    <img src="{{ asset('assets/img/' . Auth::guard('mahasiswa')->user()->foto) }}"
                        alt="Foto Mahasiswa"
                        class="rounded-circle me-3"
                        style="width: 70px; height: 70px; object-fit: cover;">
                @else
                    <div class="rounded-circle me-3 d-flex align-items-center justify-content-center bg-light"
                        style="width: 70px; height: 70px;">
                        <i class="fas fa-user text-secondary"></i>
                    </div>
                @endif
                <div>
                    <h5 class="mb-0">{{ Auth::guard('mahasiswa')->user()->nama_mhs }}</h5>
                    <p class="text-sm mb-0 text-secondary">Mahasiswa</p>
                    <form action="{{ route('logoutmahasiswa') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link text-danger text-sm p-0 m-0 align-baseline">
                            <i class="material-symbols-rounded align-middle">logout</i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Hari Ini --}}
    <div class="col-lg-8 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body row text-center">
                <div class="col-6 border-end">
                    <h6 class="text-secondary mb-1">Masuk</h6>
                    <h4 class="mb-0">
                        {{ $presensihariini && $presensihariini->jam_in ? $presensihariini->jam_in : 'Belum Absen' }}
                    </h4>
                </div>
                <div class="col-6">
                    <h6 class="text-secondary mb-1">Pulang</h6>
                    <h4 class="mb-0">
                        {{ $presensihariini && $presensihariini->jam_out ? $presensihariini->jam_out : 'Belum Absen' }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- === Menu Aksi Mahasiswa === --}}
<div class="row mb-4">
    <div class="col-lg-4 col-md-6 mb-3">
        <a href="{{ route('mahasiswa.izin') }}" class="text-decoration-none">
            <div class="card h-100 hover-shadow-sm">
                <div class="card-body text-center">
                    <div class="icon icon-shape bg-gradient-dark text-white shadow text-center border-radius-lg mb-3"
                        style="width: 60px; height: 60px; margin: 0 auto;">
                        <i class="material-symbols-rounded opacity-10 fs-3">calendar_month</i>
                    </div>
                    <h6 class="mb-0">Izin</h6>
                    <p class="text-xs text-secondary mb-0">Ajukan izin tidak hadir</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-4 col-md-6 mb-3">
        <a href="{{ route('mahasiswa.histori') }}" class="text-decoration-none">
            <div class="card h-100 hover-shadow-sm">
                <div class="card-body text-center">
                    <div class="icon icon-shape bg-gradient-dark text-white shadow text-center border-radius-lg mb-3"
                        style="width: 60px; height: 60px; margin: 0 auto;">
                        <i class="material-symbols-rounded opacity-10 fs-3">history</i>
                    </div>
                    <h6 class="mb-0">Histori</h6>
                    <p class="text-xs text-secondary mb-0">Lihat riwayat absensi</p>
                </div>
            </div>
        </a>
    </div>

    {{-- Tombol Presensi Cepat --}}
    <div class="col-lg-4 col-md-6 mb-3 text-center">
        <button id="btn-presensi-cepat" class="btn btn-danger w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
            <div class="icon icon-shape bg-gradient-danger text-white shadow text-center border-radius-lg mb-3"
                style="width: 60px; height: 60px; margin: 0 auto;">
                <i class="material-symbols-rounded opacity-10 fs-3">fingerprint</i>
            </div>
            <h6 class="mb-0">Presensi Cepat</h6>
            <p class="text-xs text-secondary mb-0">Langsung absen masuk/pulang</p>
        </button>
    </div>
</div>

{{-- Input Kamera Tersembunyi --}}
<input type="file" accept="image/*" capture="environment" id="cameraInput" style="display:none">

{{-- === Statistik & Grafik Kehadiran === --}}
@php
    $totalHari = now()->daysInMonth;
    $hadir = $rekapizin->hadir ?? 0;
    $izin = $rekapizin->jmlizin ?? 0;
    $sakit = $rekapizin->jmlsakit ?? 0;
@endphp
<div class="row mb-4">
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="mb-3">Statistik Kehadiran Bulan Ini</h6>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="mb-3">
                            <i class="material-symbols-rounded text-success fs-1">check_circle</i>
                        </div>
                        <h4 class="font-weight-bold text-success mb-1">{{ $hadir }}</h4>
                        <small class="text-muted">Hadir</small>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <i class="material-symbols-rounded text-warning fs-1">event_busy</i>
                        </div>
                        <h4 class="font-weight-bold text-warning mb-1">{{ $izin }}</h4>
                        <small class="text-muted">Izin</small>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <i class="material-symbols-rounded text-danger fs-1">medical_information</i>
                        </div>
                        <h4 class="font-weight-bold text-danger mb-1">{{ $sakit }}</h4>
                        <small class="text-muted">Sakit</small>
                    </div>
                </div>
                <hr class="my-3">
                <div class="text-center">
                    <small class="text-muted">Total hari dalam bulan: <strong>{{ $totalHari }}</strong></small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="mb-3">Grafik Kehadiran</h6>
                <canvas id="chart-hadir" class="chart-canvas" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- === Script Chart & Presensi Cepat === --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart
    const ctx = document.getElementById('chart-hadir').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Hadir', 'Izin', 'Sakit'],
            datasets: [{
                label: 'Jumlah Hari',
                data: [{{ $hadir }}, {{ $izin }}, {{ $sakit }}],
                backgroundColor: ['#4CAF50', '#FFC107', '#F44336']
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Presensi Cepat
    const btnPresensi = document.getElementById('btn-presensi-cepat');
    const cameraInput = document.getElementById('cameraInput');

    btnPresensi.addEventListener('click', () => {
        cameraInput.click();
    });

cameraInput.addEventListener('change', () => {
    const file = cameraInput.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const base64Image = e.target.result;

        // Ambil lokasi (opsional)
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                kirimPresensi(base64Image, position.coords.latitude, position.coords.longitude, 'in');
            }, function() {
                kirimPresensi(base64Image, '', '', 'in');
            });
        } else {
            kirimPresensi(base64Image, '', '', 'in');
        }
    };
    reader.readAsDataURL(file);
});

function kirimPresensi(image, lat, long, type) {
    fetch("{{ route('mahasiswa.storepresensi') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            image: image,
            lokasi: lat && long ? lat + ',' + long : '',
            catat_harian: '',
            type: type
        })
    })
    .then(res => res.json())
    .then(res => {
        if(res.status === 'success'){
            // Show success notification
            showNotification(res.message, 'success');
            // Reload page after 2 seconds
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
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notification => notification.remove());

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="material-symbols-rounded mr-2">${type === 'success' ? 'check_circle' : 'error'}</i>
                <span>${message}</span>
            </div>
        `;

        // Add to page
        document.body.appendChild(notification);

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }
</script>
@endsection
