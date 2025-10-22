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
                    <th>Foto Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Foto Pulang</th>
                    <th>Total Jam Kerja</th>
                    <th>Catatan Harian</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($histori as $d)
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
                            <td>
                                @if($d->foto_in)
                                    <img src="{{ Storage::url('uploads/presensi/' . $d->foto_in) }}"
                                         alt="Foto Masuk"
                                         class="img-thumbnail"
                                         style="width: 60px; height: 45px; object-fit: cover; cursor: pointer;"
                                         onclick="showImageModal('{{ Storage::url('uploads/presensi/' . $d->foto_in) }}', 'Foto Absen Masuk')">
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $d->jam_out }}</td>
                            <td>
                                @if($d->foto_out)
                                    <img src="{{ Storage::url('uploads/presensi/' . $d->foto_out) }}"
                                         alt="Foto Pulang"
                                         class="img-thumbnail"
                                         style="width: 60px; height: 45px; object-fit: cover; cursor: pointer;"
                                         onclick="showImageModal('{{ Storage::url('uploads/presensi/' . $d->foto_out) }}', 'Foto Absen Pulang')">
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $totalJamKerja }}</td>
                            <td>{{ $d->catat_harian ?? '-' }}</td>
                        </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Modal untuk menampilkan gambar --}}
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Foto Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Foto Absensi" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk menampilkan modal gambar
        function showImageModal(imageSrc, title) {
            document.getElementById('imageModalLabel').textContent = title;
            document.getElementById('modalImage').src = imageSrc;
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            modal.show();
        }
    </script>
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