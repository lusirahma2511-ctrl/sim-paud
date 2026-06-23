<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresensiSiswa extends Model
{
    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'guru_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
        'keterangan',
        'semester',
        'tahun_ajaran',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}