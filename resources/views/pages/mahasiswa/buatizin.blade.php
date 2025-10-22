@extends('layouts.app')

@section('content')
@include('layouts.sidebarmhs')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
<style>
    .datepicker-modal {
        max-height: 430px !important;
    }

    .datepicker-date-display {
        background-color: #800000 !important;
    }
</style>
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Form Izin</div>
    <div class="right"></div>
</div>
<!-- App Header -->
@endsection

@section('content')
<div class="row" style="margin-top:70px">
    <div class="col">
        <form method="POST" action="/presensi/storeizin" id="frmIzin">
            @csrf
            <div class="form-group">
                <input type="text" id+="tgl_izin" name="tgl_izin" class="form-control datepicker" placeholder="Tanggal">
            </div>
            <div class="form-group">
                <select name="status" id="status" class="form-control">
                    <option value="">Status</option>
                    <option value="i">Izin</option>
                    <option value="s">Sakit</option>
                </select>
            </div>
            <!-- <div class="form-group">
                <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control"
                    placeholder="Keterangan"></textarea>
            </div> -->
            <div class="form-group">
                <label for="lampiran" class="col-sm-3 col-form-label">Lampiran</label>
                <input type="file" name="lampiran" id="lampiran" class="form-control">
            </div>
            <div class="form-group">
                <button class="btn w-100" style="background-color: maroon; color: white; border: none;">Kirim</button>
            </div>
        </form>
    </div>
</div>
@endsection

<!-- DatePicker -->
@push('myscript')
    <script>
        $(document).ready(function () {
            $(".datepicker").datepicker({
                defaultDate: new Date(2025, 0, 1), // Tanggal default di awal tahun 2024
                minDate: new Date(2025, 0, 1),     // Tanggal minimum (awal 2024)
                maxDate: new Date(2026, 11, 31),   // Tanggal maksimum (akhir 2026)
                yearRange: [2024, 2026],           // Rentang tahun pada dropdown
                format: "yyyy-mm-dd"               // Format tanggal
            });

            $("#frmIzin").submit(function () {
                var tgl_izin = $("#tgl_izin").val();
                var status = $("#status").val();
                var lampiran = $("#lampiran").val();
                if (tgl_izin == "") {
                    Swal.fire({
                        title: 'Oops !'
                        , text: 'Tanggal Harus di Isi'
                        , icon: 'warning'
                    });
                    return false;
                } else if (status == "") {
                    Swal.fire({
                        title: 'Oops !'
                        , text: 'Status Harus di Isi'
                        , icon: 'warning'
                    });
                    return false;
                } else if (keterangan == "") {
                    Swal.fire({
                        title: 'Oops !'
                        , text: 'Keterangan Harus di Isi'
                        , icon: 'warning'
                    });
                    return false;
                }
            });
        });
    </script>
@endpush