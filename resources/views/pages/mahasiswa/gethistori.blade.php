@if ($histori->isEmpty())
    <div class="alert alert-outline-warning">
        <p>Data Belum Ada</p>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center">
            <thead class="table-maroon">
                <tr>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Total Jam Kerja</th>
                    <th>Catatan Harian</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($histori as $d)
                        <!-- @php
                                        // Hitung total jam kerja
                                        $jamMasuk = \Carbon\Carbon::parse($d->jam_in);
                                        $jamPulang = \Carbon\Carbon::parse($d->jam_out);
                                        $totalJamKerja = $jamMasuk->diff($jamPulang)->format('%H:%I:%S');
                                    @endphp -->

                        @php
                            $jamMasuk = \Carbon\Carbon::parse($d->jam_in);
                            $totalJamKerja = 'Belum Absen Pulang';

                            // Cek jika jam pulang tersedia, maka hitung total jam kerja
                            if (!empty($d->jam_out)) {
                                $jamPulang = \Carbon\Carbon::parse($d->jam_out);
                                $totalJamKerja = $jamMasuk->diff($jamPulang)->format('%H:%I:%S');
                            }
                        @endphp
                        <tr>
                            <td>{{ date("d-m-Y", strtotime($d->tgl_presensi)) }}</td>
                            <td>{{ $d->jam_in }}</td>
                            <td>{{ $d->jam_out }}</td>
                            <td>{{ $totalJamKerja }}</td>
                            <td>{{ $d->catat_harian ?? '-' }}</td>
                        </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@push('myscript')


<!-- @foreach ($histori as $d) -->
<!-- <ul class="listview image-listview">
        <li>
            <div class="item">
                @php
                    $path = Storage::url('uploads/absensi/' . $d->foto_in);
                @endphp -->
<!-- <img src="{{ url($path)}}" alt="image" class="image"> -->
<!-- <div class="in">
                    <div>
                        <b>{{ date("d-m-Y", strtotime($d->tgl_presensi)) }}</b><br>
                        <small class="text-muted">{{ $d->prodi }}</small>
                    </div>
                    <span class="badge {{ $d->jam_in < "07.30" ? "bg-success" : "bg-danger" }}" style="color : aliceblue;">
                        {{ $d->jam_in }}
                    </span>
                    <span class="badge bg-primary" style="color : aliceblue;">{{ $d->jam_out }}</span>
                </div>
            </div>
        </li>
    </ul> -->

<!-- <table>
        <th>Tanggal</th>
        <th>Jam Masuk</th>
        <th>Jam Pulang</th>
        <th>Total Jam Kerja</th>
        <tr>
            <td>{{ date("d-m-Y", strtotime($d->tgl_presensi)) }}</td>
            <td>{{ $d->jam_in }}</td>
            <td>{{ $d->jam_out }}</td>
            <td>{{ $total_waktu }}</td>

        </tr>
    </table>


@endforeach -->