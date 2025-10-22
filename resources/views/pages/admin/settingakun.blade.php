@extends('layouts.app')

@section('title', 'Setting Akun | Admin')

@section('content')
<div class="container-fluid py-4">

    <div class="ms-3 mb-4">
        <h3 class="mb-0 h4 font-weight-bolder">Pengaturan Akun Admin</h3>
        <p class="mb-4 text-sm text-muted">
            Kelola akun dan ubah password dengan aman.
        </p>
    </div>

    <div class="row">
        {{-- Informasi Akun --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm border-radius-lg">
                <div class="card-header pb-0 bg-gradient-dark text-white border-radius-lg">
                    <h6 class="mb-0">Informasi Akun</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2 text-sm"><strong>Nama:</strong> {{ $user->name ?? '-' }}</p>
                    <p class="mb-2 text-sm"><strong>Email:</strong> {{ $user->email ?? '-' }}</p>
                    <p class="mb-0 text-sm"><strong>Level:</strong> {{ $level }}</p>
                </div>
            </div>
        </div>

        {{-- Form Ubah Password --}}
        <div class="col-lg-8 col-md-6 mb-4">
            <div class="card shadow-sm border-radius-lg">
                <div class="card-header pb-0 bg-gradient-dark text-white border-radius-lg">
                    <h6 class="mb-0">Ubah Password</h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success text-white">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger text-white">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.ubah.password') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Password Lama</label>
                            <input type="password" name="password_lama" class="form-control border" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password_baru" class="form-control border" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_baru_confirmation" class="form-control border" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn bg-gradient-dark mb-0">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
