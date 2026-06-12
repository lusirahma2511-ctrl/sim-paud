<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SkalaNilai;



class NilaiPerkembangan extends Model
{
    protected $fillable = [
        'siswa_id',
        'kriteria_id',
        'nilai',
        'catatan',
        'guru_id',
        'semester',
        'tahun_ajaran',
    ];

    protected $casts = [
        'nilai' => 'double',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function skalaNilai()
{
    return $this->belongsTo(SkalaNilai::class, 'nilai', 'nilai');
}

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}