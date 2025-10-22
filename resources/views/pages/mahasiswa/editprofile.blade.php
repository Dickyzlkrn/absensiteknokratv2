@extends('layouts.app')

@section('content')
@include('layouts.sidebarmhs')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Edit Profile</div>
        <div class="right"></div>
    </div>
    <!-- App Header -->
@endsection
<style>
      .btn.btn-primary:hover {
            background-color: #ffc107 !important; /* warna kuning */
            border-color: #ffc107 !important;
            color: #000 !important; /* biar teks/icon tetap kebaca */
        }
    
</style>
@section('content')
    <div class="row" style="margin-top:4rem">
        <div class="col">
            @php
                $messagesuccess = Session::get('success');
                $messageerror = Session::get('error');
            @endphp

            @if($messagesuccess)
                <div class="alert alert-success">
                    {{ $messagesuccess }}
                </div>
            @endif

            @if($messageerror)
                <div class="alert alert-danger">
                    {{ $messageerror }}
                </div>
            @endif
        </div>
    </div>

    <form action="/mahasiswa/updateprofile" method="POST" enctype="multipart/form-data" style="padding-bottom: 7rem;">
        @csrf
        <div class="col">
            <!-- NPM -->
            <div class="form-group boxed" style="margin-bottom: 1px;">
                <label for="npm">NPM</label>
                <div class="input-wrapper">
                    <input type="text" class="form-control" value="{{ $mahasiswa->npm }}" name="npm" id="npm"
                        placeholder="NPM" autocomplete="off" style="padding-left: 15px;" readonly>
                </div>
            </div>

            <!-- Nama Lengkap -->
            <div class="form-group boxed" style="margin-bottom: 1px;">
                <label for="nama_mhs">Nama Lengkap</label>
                <div class="input-wrapper">
                    <input type="text" class="form-control" value="{{ $mahasiswa->nama_mhs }}" name="nama_mhs"
                        id="nama_mhs" placeholder="Nama Lengkap" autocomplete="off">
                </div>
            </div>

            <!-- Program Studi -->
            <div class="form-group boxed" style="margin-bottom: 1px;">
                <label for="prodi">Program Studi</label>
                <div class="input-wrapper">
                    <input type="text" class="form-control" value="{{ $mahasiswa->prodi }}" name="prodi" id="prodi"
                        placeholder="Program Studi" autocomplete="off">
                </div>
            </div>

            <!-- No. HP -->
            <div class="form-group boxed" style="margin-bottom: 1px;">
                <label for="nohp_mhs">No. HP</label>
                <div class="input-wrapper">
                    <input type="text" class="form-control" value="{{ $mahasiswa->nohp_mhs }}" name="nohp_mhs" id="nohp_mhs"
                        placeholder="No. HP" autocomplete="off">
                </div>
            </div>

            <!-- Upload Foto -->
            <div class="custom-file-upload" id="fileUpload1" style="text-align: center; margin-top: 1rem;">
                <input type="file" name="foto" id="fileuploadInput" accept=".png, .jpg, .jpeg" style="display: none;">
                <label for="fileuploadInput"
                    style="display: inline-block; background-color: #f0f0f0; padding: 15px; cursor: pointer; border: 2px solid #4b0000; border-radius: 5px;">
                    <span>
                        <ion-icon name="cloud-upload-outline" role="img" class="md hydrated"
                            aria-label="cloud upload outline" style="font-size: 24px; color: #4b0000;"></ion-icon>
                    </span>
                </label>
                <p><strong><i>Tap to Upload</i></strong></p>
            </div>

            <!-- Submit Button -->
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <button type="submit" class="btn btn-primary btn-block">
                        <ion-icon name="refresh-outline"></ion-icon> Update
                    </button>
                </div>
            </div>

        </div>
    </form>
@endsection
