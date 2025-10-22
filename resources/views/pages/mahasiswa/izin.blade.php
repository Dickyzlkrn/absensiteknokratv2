@extends('layouts.app')

@section('content')
@include('layouts.sidebarmhs')
<!-- App Header -->
<div class="appHeader text-light" style="background-color: #4b0000;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Data Izin / Sakit</div>
    <div class="right"></div>
</div>
<!-- App Header -->
@endsection

@section('content')
<div class="container-fluid p-0" style="margin-top: 10px;">
    <div class="row mx-0" style="
    padding-top: 37px;">
        <div class="col px-2">
            @php
                $messagesuccess = Session::get('success');
                $messageerror = Session::get('error');
            @endphp
            @if(Session::get('success'))
                <div class="alert alert-success mt-2">
                    {{ $messagesuccess }}
                </div>
            @endif
            @if(Session::get('error'))
                <div class="alert alert-danger mt-2">
                    {{ $messageerror }}
                </div>
            @endif
        </div>
    </div>

    <!-- Memberi jarak antara header dan tabel -->
    <div class="row mx-0 mt-3">
        <div class="col px-2">
            <table class="table table-striped table-bordered" style="margin: 0; width: 100%;">
                <thead style="background-color: white; color: black; border-bottom: 2px solid #dee2e6;">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Lampiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataizin as $index => $d)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ date('d-m-Y', strtotime($d->tgl_izin)) }}</td>
                            <td>{{ $d->status == 's' ? 'Sakit' : 'Izin' }}</td>
                            <td>{{ $d->lampiran }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data izin/sakit.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- FAB Button -->
<div class="fab-button bottom-right" style="margin-bottom:70px; display: flex; justify-content: center; align-items: center;">
    <a href="/presensi/buatizin" 
       class="fab btn rounded-circle shadow d-flex justify-content-center align-items-center" 
       style="width: 70px;height: 70px;font-size: 48px;position: relative;background-color: #4b0000;">
        <ion-icon name="add-outline" style="font-size: 36px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></ion-icon>
    </a>
</div>


</div>
@endsection