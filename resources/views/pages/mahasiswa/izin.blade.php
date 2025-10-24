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
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataizin as $index => $d)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ date('d-m-Y', strtotime($d->tgl_izin)) }}</td>
                            <td>
                                @if($d->status_approved == '0')
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif($d->status_approved == '1')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                                {{ $d->status == 's' ? 'Sakit' : 'Izin' }}
                            </td>
                            <td>{{ $d->deskripsi_izin ?? '-' }}</td>
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
    <button type="button" data-bs-toggle="modal" data-bs-target="#buatIzinModal"
       class="fab btn rounded-circle shadow d-flex justify-content-center align-items-center"
       style="width: 70px;height: 70px;font-size: 48px;position: relative;background-color: #4b0000;border: none;">
        <ion-icon name="add-outline" style="font-size: 36px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></ion-icon>
    </button>
</div>

{{-- Modal for Buat Izin --}}
<div class="modal fade" id="buatIzinModal" tabindex="-1" aria-labelledby="buatIzinModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buatIzinModalLabel">Form Pengajuan Izin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Display success/error messages --}}
                <div id="modalMessages"></div>

                <form id="izinForm" action="{{ route('mahasiswa.storeizin') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="tgl_izin" class="form-label">Tanggal Izin</label>
                        <input type="date" class="form-control" id="tgl_izin" name="tgl_izin" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Jenis Izin</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="">Pilih Jenis Izin</option>
                            <option value="i">Izin</option>
                            <option value="s">Sakit</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi_izin" class="form-label">Deskripsi Izin</label>
                        <textarea class="form-control" id="deskripsi_izin" name="deskripsi_izin" rows="4" placeholder="Jelaskan alasan izin secara detail..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="izinForm" class="btn btn-primary">Ajukan Izin</button>
            </div>
        </div>
    </div>
</div>

{{-- Script for modal handling --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('buatIzinModal'));
    const form = document.getElementById('izinForm');
    const messagesDiv = document.getElementById('modalMessages');

    // Handle form submission with AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                messagesDiv.innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                    data.message +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '</div>';

                // Reset form
                form.reset();

                // Close modal after 2 seconds
                setTimeout(function() {
                    modal.hide();
                    messagesDiv.innerHTML = '';
                    // Reload page to show updated data
                    location.reload();
                }, 2000);
            } else {
                // Show error message
                messagesDiv.innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                    (data.message || 'Terjadi kesalahan') +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '</div>';
            }
        })
        .catch(error => {
            messagesDiv.innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                'Terjadi kesalahan saat mengirim data' +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>';
        });
    });

    // Clear messages when modal is opened
    document.getElementById('buatIzinModal').addEventListener('show.bs.modal', function() {
        messagesDiv.innerHTML = '';
        form.reset();
    });
});
</script>

</div>
@endsection
