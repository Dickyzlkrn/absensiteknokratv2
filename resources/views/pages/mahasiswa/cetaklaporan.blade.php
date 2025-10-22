<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>A4</title>

  <!-- Normalize or reset CSS with your favorite library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

  <!-- Load paper.css for happy printing -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

  <!-- Set page size here: A5, A4 or A3 -->
  <!-- Set also "landscape" if you need -->
  <style>
    @page {
      size: A4
    }

    #title {
      /* font-family: Arial, Helvetica, sans-serif; */
      font-size: 16px;
      font-weight: bold;
      text-align: center;
      vertical-align: middle;
    }

    .tabeldatamahasiswa {
      margin-top: 40px;
    }

    .tabeldatamahasiswa {
      padding: 3px;
    }

    .tabelpresensi {
      width: 100%;
      margin-top: 20px;
      border-collapse: collapse;
    }

    .tabelpresensi tr th {
      border: 1px solid #131212;
      padding: 8px;
      background-color: #dbdbdb;
    }

    .tabelpresensi tr td {
      border: 1px solid #131212;
      padding: 5px;
      font-size: ;
    }
  </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4">

  <!-- Each sheet element should have the class "sheet" -->
  <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
  <section class="sheet padding-10mm">

    <table style="width: 100%">
      <tr>
        <td>
          <img src="{{ asset('assets/img/logouti.png') }}" width="70" height="70" alt="">
        </td>
        <td style="">
          <span id="title" text-align: center;>
            PRESENSI DAN CATATAN HARIAN MAHASISWA<br>
            {{ $namabulan[$bulan] }} {{ $tahun }}<br>
            Universitas Teknokrat Indonesia<br>
          </span>
          <span><i>Jl. ZA. Pagar Alam No.9-11, Labuhan Ratu, Kec.Kedaton, Kota Bandar Lampung</i></span>
        </td>
      </tr>
    </table>
    <table class="tabeldatamahasiswa">
      
      <tr>
        <td>Nama Mahasiswa</td>
        <td>:</td>
        <td>{{ $mahasiswa->nama_mhs }}</td>
      </tr>
      <tr>
        <td>NPM</td>
        <td>:</td>
        <td>{{ $mahasiswa->npm }}</td>
      </tr>
      <tr>
        <td>Program Studi</td>
        <td>:</td>
        <td>{{ $mahasiswa->prodi }}</td>
      </tr>
      <tr>
        <td>Tempat PKL</td>
        <td>:</td>
        <td>{{ $mahasiswa->tempat_pkl }}</td>
      </tr>
    </table>
    <table class="tabelpresensi">
      <tr>
        <th>No.</th>
        <th>Tanggal</th>
        <th>Jam Masuk</th>
        <!-- <th>Foto</th> -->
        <th>Jam Pulang</th>
        <!-- <th>Foto</th> -->
        <th>Catatan Harian</th>
        <!-- <th>Keterangan</th> -->
      </tr>
      @foreach ($presensi as $d)
      <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ date("d-m-Y", strtotime($d->tgl_presensi)) }}</td>
      <td>{{ $d->jam_in }}</td>
      <td>{{ $d->jam_out }}</td>
      <td>{{ $d->catat_harian }}</td>

      </tr>
    @endforeach
    </table>
  </section>

</body>

</html>