<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MahasiswaImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Normalisasi nama prodi untuk menangani variasi penulisan
        $prodiNormalized = $this->normalizeProdi($row['program_studi'] ?? $row['prodi'] ?? '');

        return new Mahasiswa([
            'npm' => $row['npm'],
            'nama_mhs' => $row['nama_mahasiswa'] ?? $row['nama_mhs'],
            'prodi' => $prodiNormalized,
            'nohp_mhs' => $row['no_hp'] ?? $row['nohp_mhs'],
            'tempat_pkl' => $row['tempat_pkl'] ?? null,
            'password' => Hash::make('password123'), // Default password
        ]);
    }

    public function rules(): array
    {
        return [
            '*.npm' => 'required|unique:mahasiswa,npm',
            '*.nama_mahasiswa' => 'required',
            '*.nama_mhs' => 'required_without:*.nama_mahasiswa',
            '*.program_studi' => 'required',
            '*.prodi' => 'required_without:*.program_studi',
            '*.no_hp' => 'required',
            '*.nohp_mhs' => 'required_without:*.no_hp',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.npm.required' => 'NPM wajib diisi.',
            '*.npm.unique' => 'NPM sudah terdaftar.',
            '*.nama_mahasiswa.required' => 'Nama mahasiswa wajib diisi.',
            '*.nama_mhs.required_without' => 'Nama mahasiswa wajib diisi.',
            '*.program_studi.required' => 'Program studi wajib diisi.',
            '*.prodi.required_without' => 'Program studi wajib diisi.',
            '*.no_hp.required' => 'No HP wajib diisi.',
            '*.nohp_mhs.required_without' => 'No HP wajib diisi.',
        ];
    }

    private function normalizeProdi($prodi)
    {
        if (!$prodi) return '';

        $prodi = trim(strtolower($prodi));

        // Mapping variasi penulisan prodi
        $mappings = [
            // Teknik Informatika
            'teknik informatika' => 'Teknik Informatika',
            'ti' => 'Teknik Informatika',
            'informatika' => 'Teknik Informatika',
            's1 teknik informatika' => 'Teknik Informatika',
            's1 ti' => 'Teknik Informatika',

            // Sistem Informasi
            'sistem informasi' => 'Sistem Informasi',
            'si' => 'Sistem Informasi',
            's1 sistem informasi' => 'Sistem Informasi',
            's1 si' => 'Sistem Informasi',

            // Teknik Elektro
            'teknik elektro' => 'Teknik Elektro',
            'elektro' => 'Teknik Elektro',
            's1 teknik elektro' => 'Teknik Elektro',

            // Teknik Mesin
            'teknik mesin' => 'Teknik Mesin',
            'mesin' => 'Teknik Mesin',
            's1 teknik mesin' => 'Teknik Mesin',

            // Teknik Sipil
            'teknik sipil' => 'Teknik Sipil',
            'sipil' => 'Teknik Sipil',
            's1 teknik sipil' => 'Teknik Sipil',

            // Akuntansi
            'akuntansi' => 'Akuntansi',
            's1 akuntansi' => 'Akuntansi',

            // Manajemen
            'manajemen' => 'Manajemen',
            's1 manajemen' => 'Manajemen',

            // Ilmu Komunikasi
            'ilmu komunikasi' => 'Ilmu Komunikasi',
            'komunikasi' => 'Ilmu Komunikasi',
            's1 ilmu komunikasi' => 'Ilmu Komunikasi',

            // Desain Komunikasi Visual
            'desain komunikasi visual' => 'Desain Komunikasi Visual',
            'dkv' => 'Desain Komunikasi Visual',
            's1 dkv' => 'Desain Komunikasi Visual',
        ];

        return $mappings[$prodi] ?? ucwords($prodi);
    }
}
