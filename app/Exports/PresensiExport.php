<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PresensiExport implements FromCollection, WithHeadings
{
    protected $bulan;
    protected $tahun;
    protected $npm;

    public function __construct($bulan = null, $tahun = null, $npm = null)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->npm = $npm;
    }

    public function collection()
    {
        $query = DB::table('presensi')
            ->join('mahasiswa', 'presensi.npm', '=', 'mahasiswa.npm')
            ->select(
                'presensi.npm',
                'mahasiswa.nama_mhs',
                'mahasiswa.prodi',
                'presensi.tgl_presensi',
                'presensi.jam_in',
                'presensi.jam_out',
                'presensi.lokasi_in',
                'presensi.lokasi_out',
                'presensi.catat_harian'
            );

        if ($this->npm) {
            $query->where('presensi.npm', $this->npm);
        }

        if ($this->bulan) {
            $query->whereMonth('presensi.tgl_presensi', $this->bulan);
        }

        if ($this->tahun) {
            $query->whereYear('presensi.tgl_presensi', $this->tahun);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'NPM',
            'Nama Mahasiswa',
            'Program Studi',
            'Tanggal Presensi',
            'Jam Masuk',
            'Jam Keluar',
            'Lokasi Masuk',
            'Lokasi Keluar',
            'Catatan Harian'
        ];
    }
}
