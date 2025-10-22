@extends('layouts.admin.tabler')
@section('content')
    <div class="page-header d-print-none">
        <div class="content-wrapper" style="margin-left: 250px;">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <!-- Page pre-title -->
                        <h2 class="page-title">
                            Monitoring Presensi
                        </h2>
                    </div>
                </div>
            </div>

            <div class="page-body">
                <div class="container-xl">
                    <div class="row">
                        <div class="col-12">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-icon mb-3"
                                            style="position: relative; display: flex; align-items: center;">
                                            <span class="input-icon-addon"
                                                style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); display: flex; align-items: center; justify-content: center; height: 100%;">
                                                <!-- Download SVG icon from http://tabler.io/icons/icon/user -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-month">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                                    <path d="M16 3v4" />
                                                    <path d="M8 3v4" />
                                                    <path d="M4 11h16" />
                                                    <path d="M8 14v4" />
                                                    <path d="M12 14v4" />
                                                    <path d="M16 14v4" />
                                                </svg>
                                            </span>
                                            <input type="text" id="tanggal" name="tanggal" value="" class="form-control"
                                                placeholder="Tanggal Presensi" autocomplete="off"
                                                style="padding-left: 40px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Prodi</th>
                                                    <th>Jam Masuk</th>
                                                    <th>Foto</th>
                                                    <th>Jam Pulang</th>
                                                    <th>Foto</th>
                                                </tr>
                                            </thead>
                                            <tbody id="loadpresensi"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal modal-blur fade show" id="modal-tampilkan peta" tabindex="-1" role="dialog" aria-modal="true">
                <div class="modal-dialog modal-1 modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Lokasi Presensi Mahasiswa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="loadmap">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        $(document).ready(function () {
            $("#tanggal").datepicker({
                format: 'yyyy-mm-dd', // Tambahkan format agar tanggal tampil dengan benar
                autoclose: true,
                todayHighlight: true
            });

            $("#tanggal").change(function (e) {
                var tanggal = $(this).val();
                // alert(tanggal);
                $.ajax({
                    type: 'POST',
                    url: '/getpresensi',
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal: tanggal
                    },
                    cache: false,
                    success: function (respond) {
                        $("#loadpresensi").html(respond);
                    }
                });
            });
        });
    </script>
@endpush
</div>