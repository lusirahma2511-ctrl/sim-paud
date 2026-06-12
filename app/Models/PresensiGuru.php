<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresensiGuru extends Model
{
    protected $fillable = [
        'guru_id',
        'tanggal',
        'jam_masuk',
        'status',
        'keterangan',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}