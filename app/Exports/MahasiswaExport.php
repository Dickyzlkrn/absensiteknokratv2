<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MahasiswaExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        // Return empty collection - only headers will be exported
        return collect([]);
    }

    public function headings(): array
    {
        return [
            'NPM',
            'Nama Mahasiswa',
            'Program Studi',
            'No HP',
            'Tempat PKL',
            'Kategori'
        ];
    }
}
