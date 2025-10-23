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

        // Normalisasi kategori (PKL/Magang)
        $kategoriNormalized = $this->normalizeKategori($row['kategori'] ?? '');

        return new Mahasiswa([
            'npm' => trim($row['npm']),
            'nama_mhs' => $row['nama_mahasiswa'] ?? $row['nama_mhs'],
            'prodi' => $prodiNormalized,
            'nohp_mhs' => $this->cleanPhoneNumber($row['no_hp'] ?? $row['nohp_mhs']),
            'tempat_pkl' => $row['tempat_pkl'] ?? null,
            'kategori' => $kategoriNormalized,
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

    private function normalizeKategori($kategori)
    {
        if (!$kategori) return 'PKL'; // Default to PKL if empty

        $kategori = trim(strtolower($kategori));

        // Mapping variasi penulisan kategori
        $mappings = [
            'pkl' => 'PKL',
            'praktik kerja lapangan' => 'PKL',
            'kerja praktik' => 'PKL',
            'magang' => 'Magang',
            'internship' => 'Magang',
            'kerja magang' => 'Magang',
        ];

        return $mappings[$kategori] ?? 'PKL'; // Default to PKL if not recognized
    }

    private function cleanPhoneNumber($phone)
    {
        if (!$phone) return '';

        // Remove dashes, spaces, and other non-numeric characters except +
        $phone = preg_replace('/[^\d+]/', '', $phone);

        // Remove leading + if present (assuming Indonesian numbers)
        if (str_starts_with($phone, '+62')) {
            $phone = '0' . substr($phone, 3);
        } elseif (str_starts_with($phone, '62')) {
            $phone = '0' . substr($phone, 2);
        }

        return $phone;
    }
}
