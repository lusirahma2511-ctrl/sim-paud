<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Guru extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'nik',
        'nama_guru',
        'ttl',
        'jk',
        'jabatan',
        'status',
        'alamat',
        'no_hp',
        'email',
        'barcode',
        'foto_guru',
    ];

    protected static function booted()
    {
        static::deleting(function ($guru) {
            if ($guru->user) {
                $guru->user->update(['status' => 'Nonaktif']);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function presensi()
    {
        return $this->hasMany(PresensiGuru::class, 'guru_id');
    }

    public function presensiGurus()
    {
        return $this->hasMany(PresensiGuru::class);
    }

    public function presensiSiswas()
    {
        return $this->hasMany(PresensiSiswa::class);
    }

    public function nilaiPerkembangans()
    {
        return $this->hasMany(NilaiPerkembangan::class);
    }

    public function kelas()
    {
        return $this->hasOne(Kelas::class, 'guru_id');
    }
}