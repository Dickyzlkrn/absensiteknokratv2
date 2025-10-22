<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class mpMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $prodiId = $request->input('prodi_id');
        $periodeId = $request->input('periode_id');

        $allProdi = DB::table('prodis')->get();
        $allPeriode = DB::table('periode')->get();

        $tanggalMulai = null;
        $tanggalSelesai = null;
        if ($periodeId) {
            $periode = DB::table('periode')->where('id', $periodeId)->first();
            if ($periode) {
                $tanggalMulai = $periode->tanggal_mulai;
                $tanggalSelesai = $periode->tanggal_selesai;
            }
        }

        $query = DB::table('mahasiswa')
            ->select(
                'mahasiswa.npm',
                'mahasiswa.nama_mhs',
                'presensi_in.tgl_presensi as tgl_presensi',
                'presensi_in.lokasi_in as posisi_masuk',
                'presensi_out.lokasi_out as posisi_pulang',
                'presensi_in.foto_in as foto_masuk',
                'presensi_out.foto_out as foto_pulang'
            )
            ->leftJoin('presensi as presensi_in', function($join) use ($tanggalMulai, $tanggalSelesai) {
                $join->on('mahasiswa.npm', '=', 'presensi_in.npm')
                    ->whereNotNull('presensi_in.jam_in');
                if ($tanggalMulai && $tanggalSelesai) {
                    $join->whereBetween('presensi_in.tgl_presensi', [$tanggalMulai, $tanggalSelesai]);
                }
            })
            ->leftJoin('presensi as presensi_out', function($join) use ($tanggalMulai, $tanggalSelesai) {
                $join->on('mahasiswa.npm', '=', 'presensi_out.npm')
                    ->whereNotNull('presensi_out.jam_out');
                if ($tanggalMulai && $tanggalSelesai) {
                    $join->whereBetween('presensi_out.tgl_presensi', [$tanggalMulai, $tanggalSelesai]);
                }
            });

        if ($prodiId) {
            $query->where('mahasiswa.prodi', $prodiId);
        }

        // PAGINATE: 10 data per halaman
        $mahasiswa = $query->paginate(10)->withQueryString();

        return view('pages.admin.mpmahasiswaindex', [
            'mahasiswa' => $mahasiswa,
            'allProdi' => $allProdi,
            'allPeriode' => $allPeriode,
            'selectedProdiId' => $prodiId,
            'selectedPeriodeId' => $periodeId,
        ]);
    }
}
