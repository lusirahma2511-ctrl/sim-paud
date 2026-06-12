<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $fillable = [
        'nama_kriteria',
        'kode',
        'bobot',
        'deskripsi',
    ];

    protected $casts = [
        'bobot' => 'double',
    ];

    public function nilaiPerkembangans()
    {
        return $this->hasMany(NilaiPerkembangan::class);
    }
}