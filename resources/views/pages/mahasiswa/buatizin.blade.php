@extends('layouts.app')

@section('content')
@include('layouts.sidebarmhs')

<div class="ms-3 mb-4">
    <h3 class="mb-0 h4 font-weight-bolder">Form Pengajuan Izin</h3>
    <p class="text-muted mb-4">
        Ajukan izin dengan lengkap dan jelas.
    </p>
</div>

{{-- Display success/error messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">Form Izin</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('mahasiswa.storeizin') }}" method="POST" id="izinForm">
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
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('mahasiswa.izin') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Ajukan Izin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Style tambahan --}}
<style>
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

{{-- Script untuk immediate redirect setelah success --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if there's a success message
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        // Immediate redirect to presensi page
        window.location.href = '{{ route("mahasiswa.presensi") }}';
    }
});

document.getElementById('izinForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const spinner = submitBtn.querySelector('.spinner-border');

    // Show loading spinner
    spinner.classList.remove('d-none');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...';
});
</script>

@endsection
