@extends('layouts.app')

@section('content')
@include('layouts.sidebarmhs')

<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="text-center mb-5">
                <h2 class="mb-3 font-weight-bolder text-primary">Pengaturan Akun</h2>
                <p class="text-muted lead">Kelola profil dan password akun Anda dengan mudah dan aman.</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Informasi Profil -->
        <div class="col-lg-6">
            <div class="card shadow-lg border-0 h-100 rounded-3 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                            <i class="fas fa-user-circle fa-lg"></i>
                        </div>
                        <h5 class="mb-0 fw-bold">Informasi Profil</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Current Profile Photo Display -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            @if(Auth::guard('mahasiswa')->user()->foto)
                                <img src="{{ asset('assets/img/' . Auth::guard('mahasiswa')->user()->foto) }}"
                                     alt="Foto Profil"
                                     class="rounded-circle shadow-lg border-4 border-white"
                                     style="width: 140px; height: 140px; object-fit: cover;">
                            @else
                                <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center shadow-lg border-4 border-white"
                                     style="width: 140px; height: 140px;">
                                    <i class="fas fa-user fa-4x text-white"></i>
                                </div>
                            @endif
                            <div class="position-absolute bottom-0 end-0">
                                <span class="badge bg-success rounded-pill p-2 shadow">
                                    <i class="fas fa-check fa-sm"></i>
                                </span>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 fw-semibold">Foto Profil Saat Ini</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
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
                        <div class="mb-4">
                            <label for="npm" class="form-label fw-bold text-primary mb-2">
                                <i class="fas fa-id-card me-2"></i>NPM
                            </label>
                            <input type="text" class="form-control form-control-lg bg-light border-0 rounded-3 shadow-sm" id="npm" name="npm" value="{{ Auth::guard('mahasiswa')->user()->npm }}" readonly>
                        </div>
                        <div class="mb-4">
                            <label for="nama_mhs" class="form-label fw-bold text-primary mb-2">
                                <i class="fas fa-user me-2"></i>Nama Lengkap
                            </label>
                            <input type="text" class="form-control form-control-lg border-0 rounded-3 shadow-sm" id="nama_mhs" name="nama_mhs" value="{{ Auth::guard('mahasiswa')->user()->nama_mhs }}" required placeholder="Masukkan nama lengkap">
                        </div>
                        <div class="mb-4">
                            <label for="prodi" class="form-label fw-bold text-primary mb-2">
                                <i class="fas fa-graduation-cap me-2"></i>Program Studi
                            </label>
                            <input type="text" class="form-control form-control-lg border-0 rounded-3 shadow-sm" id="prodi" name="prodi" value="{{ Auth::guard('mahasiswa')->user()->prodi }}" required placeholder="Masukkan program studi">
                        </div>
                        <div class="mb-4">
                            <label for="nohp_mhs" class="form-label fw-bold text-primary mb-2">
                                <i class="fas fa-phone me-2"></i>No. HP
                            </label>
                            <input type="text" class="form-control form-control-lg border-0 rounded-3 shadow-sm" id="nohp_mhs" name="nohp_mhs" value="{{ Auth::guard('mahasiswa')->user()->nohp_mhs }}" required placeholder="Masukkan nomor HP">
                        </div>
                        <div class="mb-4">
                            <label for="foto" class="form-label fw-bold text-primary mb-2">
                                <i class="fas fa-camera me-2"></i>Foto Profil Baru
                            </label>
                            <input type="file" class="form-control form-control-lg border-0 rounded-3 shadow-sm" id="foto" name="foto" accept="image/*">
                            <div class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>Upload foto baru jika ingin mengubah foto profil (maksimal 2MB, format: JPG, PNG)
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 shadow-sm fw-bold">
                                <i class="fas fa-save me-2"></i>Update Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ubah Password -->
        <div class="col-lg-6">
            <div class="card shadow-lg border-0 h-100 rounded-3 overflow-hidden">
                <div class="card-header bg-gradient-warning text-dark py-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                            <i class="fas fa-shield-alt fa-lg"></i>
                        </div>
                        <h5 class="mb-0 fw-bold">Ubah Password</h5>
                    </div>
                </div>
                <div class="card-body p-4 d-flex flex-column">
                    <!-- Password Strength Indicator -->
                    <div class="text-center mb-4">
                        <div class="d-inline-block p-4 bg-light rounded-circle shadow-sm">
                            <i class="fas fa-lock fa-3x text-warning"></i>
                        </div>
                        <p class="text-muted mt-3 mb-0 fw-semibold">Keamanan Akun</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
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
                        <div class="mb-4">
                            <label for="current_password" class="form-label fw-bold text-warning mb-2">
                                <i class="fas fa-key me-2"></i>Password Lama
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-lg border-0 rounded-start-3 shadow-sm" id="current_password" name="current_password" required placeholder="Masukkan password lama">
                                <button class="btn btn-outline-secondary border-0 rounded-end-3 shadow-sm" type="button" onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye" id="current_password_icon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold text-warning mb-2">
                                <i class="fas fa-lock me-2"></i>Password Baru
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-lg border-0 rounded-start-3 shadow-sm" id="password" name="password" required placeholder="Masukkan password baru (minimal 8 karakter)">
                                <button class="btn btn-outline-secondary border-0 rounded-end-3 shadow-sm" type="button" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password_icon"></i>
                                </button>
                            </div>
                            <div class="form-text mt-2" id="password-strength"></div>
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold text-warning mb-2">
                                <i class="fas fa-check-circle me-2"></i>Konfirmasi Password Baru
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-lg border-0 rounded-start-3 shadow-sm" id="password_confirmation" name="password_confirmation" required placeholder="Konfirmasi password baru">
                                <button class="btn btn-outline-secondary border-0 rounded-end-3 shadow-sm" type="button" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="password_confirmation_icon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-grid mt-auto">
                            <button type="submit" class="btn btn-warning btn-lg rounded-3 shadow-sm fw-bold">
                                <i class="fas fa-key me-2"></i>Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
.card {
    transition: all 0.3s ease;
    border: none !important;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
}
.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6b5b95 100%);
    border-color: #5a6fd8;
}
.btn-warning:hover {
    background: linear-gradient(135deg, #e083e8 0%, #e74c3c 100%);
    border-color: #e083e8;
}
.border-4 {
    border-width: 4px !important;
}
.rounded-3 {
    border-radius: 1rem !important;
}
.shadow-lg {
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
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

    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;

    switch(strength) {
        case 0:
        case 1:
            strengthDiv.innerHTML = '<small class="text-danger fw-semibold"><i class="fas fa-times-circle me-1"></i>Password sangat lemah</small>';
            break;
        case 2:
            strengthDiv.innerHTML = '<small class="text-warning fw-semibold"><i class="fas fa-exclamation-triangle me-1"></i>Password lemah</small>';
            break;
        case 3:
            strengthDiv.innerHTML = '<small class="text-info fw-semibold"><i class="fas fa-check me-1"></i>Password cukup kuat</small>';
            break;
        case 4:
        case 5:
            strengthDiv.innerHTML = '<small class="text-success fw-semibold"><i class="fas fa-check-circle me-1"></i>Password kuat</small>';
            break;
    }
});
</script>

@endsection
