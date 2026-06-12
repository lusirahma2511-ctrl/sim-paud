<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Siswa::select('nis','nama_siswa','jk','ttl','alamat')->get();
    }

    public function headings(): array
    {
        return ['NIS', 'Nama Siswa', 'Jenis Kelamin', 'Tanggal Lahir', 'Alamat'];
    }
}
