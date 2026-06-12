<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $fillable = [
        'nama_kelas',
        'guru_id',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

}