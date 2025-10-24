<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>SIPERMATA - Rekap Presensi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
        }

        #title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }

        .tabelpresensi {
            width: 100%;
            border-collapse: collapse;
            font-size: 7px;
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .tabelpresensi thead {
            page-break-after: avoid;
        }

        .tabelpresensi tbody tr {
            page-break-inside: avoid;
        }

        .tabelpresensi th,
        .tabelpresensi td {
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
            vertical-align: middle;
        }

        .header-table {
            width: 100%;
            margin-bottom: 10px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .footer {
            margin-top: 60px;
            width: 100%;
        }

        .footer td {
            text-align: center;
        }

        .sheet {
            page-break-after: always;
        }
    </style>
</head>

<body class="A4 landscape">
    @if(isset($showAllData) && $showAllData)
        @foreach ($dataPeriods as $period)
            @php
                $bulanLoop = $period->bulan;
                $tahunLoop = $period->tahun;
                $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulanLoop, $tahunLoop);
                $hariPerHalaman = 20; // 20 days per page
                $totalHalaman = ceil($jumlahHari / $hariPerHalaman);
                $currentPage = 1;
            @endphp

            @for ($halaman = 1; $halaman <= $totalHalaman; $halaman++)
                @php
                    $hariAwal = (($halaman - 1) * $hariPerHalaman) + 1;
                    $hariAkhir = min($halaman * $hariPerHalaman, $jumlahHari);
                @endphp
                <section class="sheet padding-10mm">
                    <table class="header-table">
                        <tr>
                            <td width="70">
                                <img src="{{ asset('assets/img/logo.png') }}" width="70" height="70" alt="Logo">
                            </td>
                            <td>
                                <div id="title">
                                    PRESENSI DAN CATATAN HARIAN MAHASISWA<br>
                                    {{ $namabulan[$bulanLoop] }} {{ $tahunLoop }}<br>
                                    Universitas Teknokrat Indonesia
                                </div>
                                <div style="text-align: center;">
                                    <i>Jl. ZA. Pagar Alam No.9-11, Labuhan Ratu, Kedaton, Bandar Lampung</i>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <table class="tabelpresensi">
                        <thead>
                            <tr>
                                <th rowspan="3" style="width:60px;">NPM</th>
                                <th rowspan="3" style="width:150px;">Nama Mahasiswa</th>
                                <th colspan="{{ $hariAkhir - $hariAwal + 1 }}">Tanggal {{ $hariAwal }}-{{ $hariAkhir }}</th>
                            </tr>
                            <tr>
                                @for ($i = $hariAwal; $i <= $hariAkhir; $i++)
                                    <th>{{ $i }}</th>
                                @endfor
                            </tr>
                            <tr>
                                @for ($i = $hariAwal; $i <= $hariAkhir; $i++)
                                    <th style="font-weight:normal;">Jam Masuk / Jam Pulang</th>
                                @endfor
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($rekap as $mhs)
                                <tr>
                                    <td rowspan="2">{{ $mhs->npm }}</td>
                                    <td rowspan="2" style="text-align:left;">{{ $mhs->nama_mhs }}</td>

                                    {{-- Baris jam masuk --}}
                                    @for ($i = $hariAwal; $i <= $hariAkhir; $i++)
                                        @php
                                            $tanggal = sprintf('%04d-%02d-%02d', $tahunLoop, $bulanLoop, $i);
                                            $presensi = DB::table('presensi')
                                                ->where('npm', $mhs->npm)
                                                ->where('tgl_presensi', $tanggal)
                                                ->first();
                                        @endphp
                                        <td>{{ $presensi ? $presensi->jam_in : '-' }}</td>
                                    @endfor
                                </tr>
                                <tr>
                                    {{-- Baris jam pulang --}}
                                    @for ($i = $hariAwal; $i <= $hariAkhir; $i++)
                                        @php
                                            $tanggal = sprintf('%04d-%02d-%02d', $tahunLoop, $bulanLoop, $i);
                                            $presensi = DB::table('presensi')
                                                ->where('npm', $mhs->npm)
                                                ->where('tgl_presensi', $tanggal)
                                                ->first();
                                        @endphp
                                        <td>{{ $presensi ? $presensi->jam_out : '-' }}</td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table class="footer">
                        <tr>
                            <td></td>
                            <td>Bandar Lampung, {{ date('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <td style="height:80px;">
                                <u>Nama</u><br>
                                <i><b>P3LT</b></i>
                            </td>
                            <td>
                                <u>Nama</u><br>
                                <i><b>Petinggi</b></i>
                            </td>
                        </tr>
                    </table>

                    <div style="text-align: right; font-size: 10px;">
                        Halaman {{ $currentPage++ }} dari {{ $totalHalaman }}
                    </div>
                </section>
            @endfor
        @endforeach
    @else
        @php
            $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            $hariPerHalaman = 20; // 20 days per page
            $totalHalaman = ceil($jumlahHari / $hariPerHalaman);
            $currentPage = 1;
        @endphp

        @for ($halaman = 1; $halaman <= $totalHalaman; $halaman++)
            @php
                $hariAwal = (($halaman - 1) * $hariPerHalaman) + 1;
                $hariAkhir = min($halaman * $hariPerHalaman, $jumlahHari);
            @endphp
            <section class="sheet padding-10mm">
                <table class="header-table">
                    <tr>
                        <td width="70">
                            <img src="{{ asset('assets/img/logo.png') }}" width="70" height="70" alt="Logo">
                        </td>
                        <td>
                            <div id="title">
                                PRESENSI DAN CATATAN HARIAN MAHASISWA<br>
                                {{ $namabulan[$bulan] }} {{ $tahun }}<br>
                                Universitas Teknokrat Indonesia
                            </div>
                            <div style="text-align: center;">
                                <i>Jl. ZA. Pagar Alam No.9-11, Labuhan Ratu, Kedaton, Bandar Lampung</i>
                            </div>
                        </td>
                    </tr>
                </table>

                <table class="tabelpresensi">
                    <thead>
                        <tr>
                            <th rowspan="3" style="width:60px;">NPM</th>
                            <th rowspan="3" style="width:150px;">Nama Mahasiswa</th>
                            <th colspan="{{ $hariAkhir - $hariAwal + 1 }}">Tanggal {{ $hariAwal }}-{{ $hariAkhir }}</th>
                        </tr>
                        <tr>
                            @for ($i = $hariAwal; $i <= $hariAkhir; $i++)
                                <th>{{ $i }}</th>
                            @endfor
                        </tr>
                        <tr>
                            @for ($i = $hariAwal; $i <= $hariAkhir; $i++)
                                <th style="font-weight:normal;">Jam Masuk / Jam Pulang</th>
                            @endfor
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($rekap as $mhs)
                            <tr>
                                <td rowspan="2">{{ $mhs->npm }}</td>
                                <td rowspan="2" style="text-align:left;">{{ $mhs->nama_mhs }}</td>

                                {{-- Baris jam masuk --}}
                                @for ($i = $hariAwal; $i <= $hariAkhir; $i++)
                                    @php
                                        $tanggal = sprintf('%04d-%02d-%02d', $tahun, $bulan, $i);
                                        $presensi = DB::table('presensi')
                                            ->where('npm', $mhs->npm)
                                            ->where('tgl_presensi', $tanggal)
                                            ->first();
                                    @endphp
                                    <td>{{ $presensi ? $presensi->jam_in : '-' }}</td>
                                @endfor
                            </tr>
                            <tr>
                                {{-- Baris jam pulang --}}
                                @for ($i = $hariAwal; $i <= $hariAkhir; $i++)
                                    @php
                                        $tanggal = sprintf('%04d-%02d-%02d', $tahun, $bulan, $i);
                                        $presensi = DB::table('presensi')
                                            ->where('npm', $mhs->npm)
                                            ->where('tgl_presensi', $tanggal)
                                            ->first();
                                    @endphp
                                    <td>{{ $presensi ? $presensi->jam_out : '-' }}</td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="footer">
                    <tr>
                        <td></td>
                        <td>Bandar Lampung, {{ date('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <td style="height:80px;">
                            <u>Nama</u><br>
                            <i><b>P3LT</b></i>
                        </td>
                        <td>
                            <u>Nama</u><br>
                            <i><b>Petinggi</b></i>
                        </td>
                    </tr>
                </table>

                <div style="text-align: right; font-size: 10px;">
                    Halaman {{ $currentPage++ }} dari {{ $totalHalaman }}
                </div>
            </section>
        @endfor
    @endif
</body>

</html>
