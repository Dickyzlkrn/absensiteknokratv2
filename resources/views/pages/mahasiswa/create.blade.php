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
        <div class="pageTitle">Presensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
    <style>
        .webcame-capture,
        .webcame-capture video {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
        }

        /* peta */
        #map {
            height: 200px;
        }
        #takeabsen:hover {
            background-color: #ffe200 !important;
            border-color: #ffe200 !important;
        }
    </style>
    <!-- leaflet js/tampilin peta -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@endsection

<!-- Kamera dan Lokasi -->
@section('content')
    <div class="row">
        <div class="col">
            <input type="hidden" id="lokasi">
            <div class="webcam-capture"></div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            @if ($cek > 0)
                <div class="form-group">
                    <textarea name="catat_harian" id="catat_harian" cols="30" rows="5" class="form-control"
                        placeholder="Catatan Harian" required oninvalid="this.setCustomValidity('Harap isi catatan harian')"
                        oninput="this.setCustomValidity('')"></textarea>
                </div>
                <button id="takeabsen" class="btn btn-danger btn-block">
                    <ion-icon name="camera-outline"></ion-icon>
                    Absen Pulang
                </button>
            @else
                <button id="takeabsen" class="btn btn-primary btn-block">
                    <ion-icon name="camera-outline"></ion-icon>
                    Absen Masuk
                </button>
            @endif

        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <div id="map"></div>
        </div>

    </div>
@endsection

@push('myscript')
    <script>
        Webcam.set({
            height: 480,
            widht: 640,
            image_format: 'jpeg',
            jpeg_quality: 80
        });

        Webcam.attach('.webcam-capture');

        var lokasi = document.getElementById('lokasi');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(succesCallback, errorCallback);
        }

        function succesCallback(position) {
            lokasi.value = position.coords.latitude + "," + position.coords.longitude;
            var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 15);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
        }

        function errorCallback() {

        }

        $("#takeabsen").click(function (e) {
            var catatan = $("#catat_harian");
            if (catatan.length && catatan.val().trim() === "") {
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Harap isi catatan harian terlebih dahulu.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return; // Hentikan proses absen
            }

            Webcam.snap(function (uri) {
                image = uri;
            });

            var lokasi = $("#lokasi").val();
            var catat_harian = $("#catat_harian").val();

            $.ajax({
                type: 'POST',
                url: '/presensi/store',
                data: {
                    _token: "{{ csrf_token() }}",
                    image: image,
                    lokasi: lokasi,
                    catat_harian: catat_harian
                },
                cache: false,
                success: function (respond) {
                    var status = respond.split("|");
                    if (status[0] == "succes") {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: status[1],
                            icon: 'success'
                        });
                        setTimeout(function () {
                            location.href = '/dashboard';
                        }, 3000);
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Maaf Gagal Absen',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error Details:", xhr.responseText);
                    alert('Ajax error: ' + error);
                }
            });
        });


    </script>
@endpush

<!-- skip video 7, 10, 11 yang menyimpan data presensi ke database, ajax eror -->