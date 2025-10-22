@foreach ($presensi as $d)
    @php
        $foto_in = Storage::url('uploads/' . $d->foto_in);
        $foto_out = Storage::url('uploads/' . $d->foto_out);
    @endphp
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $d->npm }}</td>
        <td>{{ $d->nama_mhs }}</td>
        <td>{{ $d->prodi }}</td>
        <td>{{ $d->jam_in }}</td>
        <td>
            <img src="{{ url($foto_in) }}" class="avatar" alt="">
        </td>
        <<td>{!! $d->jam_out != null ? $d->jam_out : '<span class="badge bg-danger">Belum Absen Pulang</span>' !!}</td>

        <td>
            @if ($d->jam_out != null)
            <img src="{{ url($foto_out) }}" class="avatar" alt="">
            @else
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-hourglass-low"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6.5 17h11" /><path d="M6 20v-2a6 6 0 1 1 12 0v2a1 1 0 0 1 -1 1h-10a1 1 0 0 1 -1 -1z" /><path d="M6 4v2a6 6 0 1 0 12 0v-2a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1z" /></svg>
        </td>

        <td>
            <a href="#" class="btn btn-primary tampilkanpeta" id="{{ $d->id}}">
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" /></svg>
            </a>

        </td>
    </tr>

@endforeach

<script>
    $(function(){
        $(".tampilkanpeta").click(function (e) {
            var id = $(this).attr("id");
            $.ajax({
                type:'POST',
                url:'/tampilkanpeta',
                data:{
                    _token: "{{ csrf_token}}",
                    id:id
                },
                cache:False,
                success:function(respond){
                    $("#loadmap").html(respond);
                }
            });
            $("#modal-tampilkanpeta").modal("show");
        });
    });
</script>
