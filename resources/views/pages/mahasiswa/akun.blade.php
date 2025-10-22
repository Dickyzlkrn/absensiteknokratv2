@extends('layouts.app')

@section('content')
@include('layouts.sidebarmhs')

<div class="ms-3 mb-4">
    <h3 class="mb-0 h4 font-weight-bolder">Pengaturan Akun</h3>
    <p class="mb-4">
        Kelola profil dan password akun Anda.
    </p>
</div>

<div class="row">
    <!-- Informasi Profil -->
    <div class="col-lg-6">
        <div class="card shadow border-0 h-100">
            <div class="card-header bg-gradient-primary text-white">
                <h6 class="mb-0">
                    <i class="fas fa-user-circle me-2"></i>Informasi Profil
                </h6>
            </div>
            <div class="card-body">
                <!-- Current Profile Photo Display -->
                <div class="text-center mb-4">
                    <div class="position-relative d-inline-block">
                        @if(Auth::guard('mahasiswa')->user()->foto)
                            <img src="{{ asset('assets/img/' . Auth::guard('mahasiswa')->user()->foto) }}"
                                 alt="Foto Profil"
                                 class="rounded-circle shadow"
                                 style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #fff;">
                        @else
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center shadow"
                                 style="width: 120px; height: 120px; border: 4px solid #fff;">
                                <i class="fas fa-user fa-3x text-secondary"></i>
                            </div>
                        @endif
                        <div class="position-absolute bottom-0 end-0">
                            <span class="badge bg-success rounded-pill p-2">
                                <i class="fas fa-check"></i>
                            </span>
                        </div>
                    </div>
                    <p class="text-muted mt-2 mb-0">Foto Profil Saat Ini</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('mahasiswa.updateprofile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="npm" class="form-label fw-bold text-primary">
                            <i class="fas fa-id-card me-1"></i>NPM
                        </label>
                        <input type="text" class="form-control form-control-lg bg-light border-0" id="npm" name="npm" value="{{ Auth::guard('mahasiswa')->user()->npm }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nama_mhs" class="form-label fw-bold text-primary">
                            <i class="fas fa-user me-1"></i>Nama Lengkap
                        </label>
                        <input type="text" class="form-control form-control-lg shadow-sm" id="nama_mhs" name="nama_mhs" value="{{ Auth::guard('mahasiswa')->user()->nama_mhs }}" required placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="mb-3">
                        <label for="prodi" class="form-label fw-bold text-primary">
                            <i class="fas fa-graduation-cap me-1"></i>Program Studi
                        </label>
                        <input type="text" class="form-control form-control-lg shadow-sm" id="prodi" name="prodi" value="{{ Auth::guard('mahasiswa')->user()->prodi }}" required placeholder="Masukkan program studi">
                    </div>
                    <div class="mb-3">
                        <label for="nohp_mhs" class="form-label fw-bold text-primary">
                            <i class="fas fa-phone me-1"></i>No. HP
                        </label>
                        <input type="text" class="form-control form-control-lg shadow-sm" id="nohp_mhs" name="nohp_mhs" value="{{ Auth::guard('mahasiswa')->user()->nohp_mhs }}" required placeholder="Masukkan nomor HP">
                    </div>
                    <div class="mb-4">
                        <label for="foto" class="form-label fw-bold text-primary">
                            <i class="fas fa-camera me-1"></i>Foto Profil Baru
                        </label>
                        <input type="file" class="form-control form-control-lg shadow-sm" id="foto" name="foto" accept="image/*">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>Upload foto baru jika ingin mengubah foto profil (maksimal 2MB, format: JPG, PNG)
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                            <i class="fas fa-save me-2"></i>Update Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Ubah Password -->
    <div class="col-lg-6">
        <div class="card shadow border-0 h-100">
            <div class="card-header bg-gradient-warning text-dark">
                <h6 class="mb-0">
                    <i class="fas fa-shield-alt me-2"></i>Ubah Password
                </h6>
            </div>
            <div class="card-body d-flex flex-column">
                <!-- Password Strength Indicator -->
                <div class="text-center mb-4">
                    <div class="d-inline-block p-3 bg-light rounded-circle">
                        <i class="fas fa-lock fa-3x text-warning"></i>
                    </div>
                    <p class="text-muted mt-2 mb-0">Keamanan Akun</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('mahasiswa.resetpassword') }}" method="POST" class="flex-grow-1">
                    @csrf
                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-bold text-warning">
                            <i class="fas fa-key me-1"></i>Password Lama
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control form-control-lg shadow-sm" id="current_password" name="current_password" required placeholder="Masukkan password lama">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                <i class="fas fa-eye" id="current_password_icon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold text-warning">
                            <i class="fas fa-lock me-1"></i>Password Baru
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control form-control-lg shadow-sm" id="password" name="password" required placeholder="Masukkan password baru (minimal 8 karakter)">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="password_icon"></i>
                            </button>
                        </div>
                        <div class="form-text" id="password-strength"></div>
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-bold text-warning">
                            <i class="fas fa-check-circle me-1"></i>Konfirmasi Password Baru
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control form-control-lg shadow-sm" id="password_confirmation" name="password_confirmation" required placeholder="Konfirmasi password baru">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye" id="password_confirmation_icon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="d-grid mt-auto">
                        <button type="submit" class="btn btn-warning btn-lg shadow-sm">
                            <i class="fas fa-key me-2"></i>Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}
.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
}
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}
</style>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '_icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthDiv = document.getElementById('password-strength');
    let strength = 0;
    let feedback = [];

    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;

    switch(strength) {
        case 0:
        case 1:
            strengthDiv.innerHTML = '<small class="text-danger"><i class="fas fa-times"></i> Password sangat lemah</small>';
            break;
        case 2:
            strengthDiv.innerHTML = '<small class="text-warning"><i class="fas fa-exclamation-triangle"></i> Password lemah</small>';
            break;
        case 3:
            strengthDiv.innerHTML = '<small class="text-info"><i class="fas fa-check"></i> Password cukup kuat</small>';
            break;
        case 4:
        case 5:
            strengthDiv.innerHTML = '<small class="text-success"><i class="fas fa-check-circle"></i> Password kuat</small>';
            break;
    }
});
</script>

@endsection
