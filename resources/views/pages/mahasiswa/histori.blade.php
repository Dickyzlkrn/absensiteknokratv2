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
        <div class="pageTitle">Histori Presensi</div>
        <div class="right"></div>
    </div>
    <!-- App Header -->
@endsection

@section('content')
    <div class="row" style="margin-top:70px">
        <div class="col">
            <div class="row g-2 align-items-end">
                <div class="col-md-4 d-flex flex-column mb-3">
                    <!-- Label untuk Bulan -->
                    <label for="bulan" class="form-label">Bulan</label>
                    <select name="bulan" id="bulan" class="form-control">
                        <option value="">Pilih Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ date("m") == $i ? 'selected' : '' }}>{{ $namabulan[$i] }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4 d-flex flex-column  mb-3"> <!-- Tambahkan mb-2 -->
                    <!-- Label untuk Tahun -->
                    <label for="tahun" class="form-label">Tahun</label>
                    <select name="tahun" id="tahun" class="form-control">
                        <option value="">Pilih Tahun</option>
                        @php
                            $tahunmulai = 2024;
                            $tahunskrg = date("Y");
                        @endphp
                        @for($tahun = $tahunmulai; $tahun <= $tahunskrg; $tahun++)
                            <option value="{{ $tahun }}" {{ date("Y") == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end  mb-3">
                    <button class="btn btn-primary text-white hover:text-white" id="getdata">

                        <ion-icon name="search-outline" style="font-size: 18px;"></ion-icon>
                        <span>Cari</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    



    <!-- Header untuk Tabel
    <div class="row mt-3">
        <div class="col-12">
            <h4 class="text-center">Tanggal dan Jam Absen</h4>
        </div>
    </div> -->
    <div class="row">
        <div class="col" id="showhistori"></div>
    </div>
@endsection


@push('myscript')
    <script>
        $(function () {
            $("#getdata").click(function (e) {
                var bulan = $("#bulan").val();
                var tahun = $("#tahun").val();
                $.ajax({
                    type: 'POST',
                    url: '/gethistori',
                    data: {
                        _token: "{{ csrf_token() }}"
                        , bulan: bulan
                        , tahun: tahun
                    }
                    , cache: false
                    , success: function (respond) {
                        $("#showhistori").html(respond);
                        console.log(respond);
                    },
                    error: function (xhr, status, error) {
                        // Debugging: Log error information if the request fails
                        console.log("AJAX Error: " + status + " - " + error);
                        console.log("XHR responseText: ", xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush