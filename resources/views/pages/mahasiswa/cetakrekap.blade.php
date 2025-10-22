<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Rekap Presensi</title>
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
            margin-top: 20px;
            border-collapse: collapse;
            font-size: 8px;
            page-break-inside: auto;
        }

        .tabelpresensi th,
        .tabelpresensi td {
            border: 1px solid #000;
            padding: 2px;
            text-align: center;
            word-break: break-word;
        }

        .sheet {
            page-break-after: always;
        }

        .header-table {
            width: 100%;
            margin-bottom: 10px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .footer {
            margin-top: 80px;
        }

        .footer td {
            text-align: center;
            vertical-align: bottom;
        }
    </style>
</head>

<body class="A4 landscape">
    @php
        $perPage = 20;
        $chunks = $rekap->chunk($perPage);
        $totalPages = $chunks->count();
        $currentPage = 1;
    @endphp

    @foreach ($chunks as $chunk)
        <section class="sheet padding-10mm">
            <table class="header-table">
                <tr>
                    <td width="70">
                        <img src="{{ asset('assets/img/logouti.png') }}" width="70" height="70" alt="Logo">
                    </td>
                    <td>
                        <div id="title">
                            PRESENSI DAN CATATAN HARIAN MAHASISWA<br>
                            {{ $namabulan[$bulan] }} {{ $tahun }}<br>
                            Universitas Teknokrat Indonesia
                        </div>
                        <div style="text-align: center;">
                            <i>Jl. ZA. Pagar Alam No.9-11, Labuhan Ratu, Kec. Kedaton, Kota Bandar Lampung</i>
                        </div>
                    </td>
                </tr>
            </table>

            <table class="tabelpresensi">
                <tr>
                    <th rowspan="2">NPM</th>
                    <th rowspan="2">Nama Mahasiswa</th>
                    @for ($i = 1; $i <= 31; $i++)
                        <th>{{ $i }}</th>
                    @endfor
                </tr>
                <tr>
                    @for ($i = 1; $i <= 31; $i++)
                        <th>Jam</th>
                    @endfor
                </tr>

                @foreach ($chunk as $d)
                    <tr>
                        <td>{{ $d->npm }}</td>
                        <td>{{ $d->nama_mhs }}</td>
                        @for ($i = 1; $i <= 31; $i++)
                            @php
                                $tglKey = 'tgl_' . $i;
                            @endphp
                            <td>{{ $d->$tglKey ?? '-' }}</td>
                        @endfor
                    </tr>
                @endforeach
            </table>

            <table width="100%" class="footer">
                <tr>
                    <td></td>
                    <td>Bandar Lampung, {{ date('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td style="height: 80px;">
                        <u>Nama</u><br>
                        <i><b>P3LT</b></i>
                    </td>
                    <td>
                        <u>Nama</u><br>
                        <i><b>Petinggi</b></i>
                    </td>
                </tr>
            </table>

            <div style="text-align: right; font-size: 10px;">Halaman {{ $currentPage++ }} dari {{ $totalPages }}</div>
        </section>
    @endforeach
</body>

</html>
