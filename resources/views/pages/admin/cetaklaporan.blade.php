<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Cetak Laporan Presensi</title>
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
    <section class="sheet padding-10mm">
        <table class="header-table">
            <tr>
                <td width="70">
                    <img src="{{ asset('assets/img/logo.png') }}" width="70" height="70" alt="Logo">
                </td>
                <td>
                    <div id="title">
                        LAPORAN PRESENSI MAHASISWA<br>
                        {{ $namabulan[$bulan] ?? 'Semua Bulan' }} {{ $tahun ?? date('Y') }}<br>
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
                    <th rowspan="2">No</th>
                    <th rowspan="2">NPM</th>
                    <th rowspan="2">Nama Mahasiswa</th>
                    <th rowspan="2">Prodi</th>
                    <th rowspan="2">Tanggal</th>
                    <th colspan="2">Jam</th>
                    <th rowspan="2">Lokasi Masuk</th>
                    <th rowspan="2">Lokasi Keluar</th>
                    <th rowspan="2">Catatan Harian</th>
                </tr>
                <tr>
                    <th>Masuk</th>
                    <th>Keluar</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($presensi as $p)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $p->npm }}</td>
                        <td style="text-align:left;">{{ $p->nama_mhs }}</td>
                        <td>{{ $p->prodi }}</td>
                        <td>{{ date('d-m-Y', strtotime($p->tgl_presensi)) }}</td>
                        <td>{{ $p->jam_in ? date('H:i', strtotime($p->jam_in)) : '-' }}</td>
                        <td>{{ $p->jam_out ? date('H:i', strtotime($p->jam_out)) : '-' }}</td>
                        <td>{{ $p->lokasi_in ?? '-' }}</td>
                        <td>{{ $p->lokasi_out ?? '-' }}</td>
                        <td>{{ $p->catat_harian ?? '-' }}</td>
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
            Halaman 1 dari 1
        </div>
    </section>
</body>

</html>
