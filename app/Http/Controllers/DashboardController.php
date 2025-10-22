<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard Mahasiswa
     */
    public function index()
    {
        if (Auth::guard('mahasiswa')->check()) {

            $npm = Auth::guard('mahasiswa')->user()->npm;

            if (!empty($npm)) {
                $bulanini = date("m") * 1;
                $tahunini = date("Y");
                $hariini = date("Y-m-d");

                // Presensi hari ini
                $presensihariini = DB::table('presensi')
                    ->join('mahasiswa', 'presensi.npm', '=', 'mahasiswa.npm')
                    ->where('presensi.npm', $npm)
                    ->where('presensi.tgl_presensi', $hariini)
                    ->select('presensi.*', 'mahasiswa.nama_mhs', 'mahasiswa.foto')
                    ->first();

                // Riwayat bulan ini
                $historibulanini = DB::table('presensi')
                    ->where('npm', $npm)
                    ->whereRaw('MONTH(tgl_presensi) = ?', [$bulanini])
                    ->whereRaw('YEAR(tgl_presensi) = ?', [$tahunini])
                    ->orderBy('tgl_presensi')
                    ->get();

                // Rekap izin/sakit bulan ini
                $rekapizin = DB::table('pengajuan_izin')
                    ->selectRaw('
                        SUM(IF(status="i",1,0)) as jmlizin,
                        SUM(IF(status="s",1,0)) as jmlsakit
                    ')
                    ->where('npm', $npm)
                    ->whereRaw('MONTH(tgl_izin) = ?', [$bulanini])
                    ->whereRaw('YEAR(tgl_izin) = ?', [$tahunini])
                    ->first();

                // Hitung hadir dari presensi
                $hadir = DB::table('presensi')
                    ->where('npm', $npm)
                    ->whereRaw('MONTH(tgl_presensi) = ?', [$bulanini])
                    ->whereRaw('YEAR(tgl_presensi) = ?', [$tahunini])
                    ->count();

                $rekapizin->hadir = $hadir;

                // Jika belum presensi hari ini
                if (!$presensihariini) {
                    $presensihariini = DB::table('mahasiswa')
                        ->where('npm', $npm)
                        ->select('mahasiswa.nama_mhs', 'mahasiswa.foto')
                        ->first();

                    $presensihariini->jam_in = null;
                    $presensihariini->jam_out = null;
                }

                return view('pages.mahasiswa.dashboardmahasiswa', compact(
                    'presensihariini',
                    'historibulanini',
                    'rekapizin'
                ));
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }

    /**
     * Dashboard Admin
     */
    public function dashboardadmin(Request $request)
    {
        $hariini = date('Y-m-d');
        $bulanini = date('m');
        $tahunini = date('Y');

        // Jumlah mahasiswa aktif
        $totalMahasiswa = DB::table('mahasiswa')->count();

        // Jumlah mahasiswa hadir hari ini
        $hadirHariIni = DB::table('presensi')
            ->whereDate('tgl_presensi', $hariini)
            ->count();

        // Jumlah izin & sakit bulan ini
        $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw('
                SUM(IF(status="i",1,0)) as jmlizin,
                SUM(IF(status="s",1,0)) as jmlsakit
            ')
            ->whereMonth('tgl_izin', $bulanini)
            ->whereYear('tgl_izin', $tahunini)
            ->first();

        // Data absensi hari ini
        $absensiHariIni = DB::table('presensi')
            ->join('mahasiswa', 'presensi.npm', '=', 'mahasiswa.npm')
            ->select(
                'mahasiswa.nama_mhs',
                'mahasiswa.npm',
                'presensi.tgl_presensi',
                'presensi.jam_in',
                'presensi.jam_out',
                'presensi.catat_harian'
            )
            ->whereDate('presensi.tgl_presensi', $hariini)
            ->orderBy('mahasiswa.nama_mhs')
            ->get();

        // 📊 Grafik kehadiran PER BULAN
        $grafikPresensi = DB::table('presensi')
            ->selectRaw('
                DATE_FORMAT(tgl_presensi, "%Y-%m") as bulan,
                COUNT(npm) as jumlah
            ')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Ubah label bulan ke format lebih enak dibaca (Jan, Feb, dst)
        $labels = $grafikPresensi->map(function ($item) {
            return date("M Y", strtotime($item->bulan . "-01"));
        });

        $dataJumlah = $grafikPresensi->pluck('jumlah');

        return view('pages.admin.dashboardadmin', compact(
            'totalMahasiswa',
            'hadirHariIni',
            'rekapizin',
            'absensiHariIni',
            'labels',
            'dataJumlah'
        ));
    }
}
