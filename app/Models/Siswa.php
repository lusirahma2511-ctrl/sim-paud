<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        'orang_tua_id', 'kelas_id', 'nik', 'nama_siswa', 'nama_panggilan',
        'nisn', 'jk', 'tempat_lahir', 'tanggal_lahir', 'agama',
        'anak_ke', 'jumlah_saudara', 'alamat', 'barcode', 'foto', 'password',
        'status'
    ];

    protected static function booted()
    {
        static::deleting(function ($siswa) {
            $orangTua = $siswa->orangTua;
            if ($orangTua) {
                // Count other siswa that belong to this orang tua
                $otherSiswaCount = self::where('orang_tua_id', $orangTua->id)
                    ->where('id', '!=', $siswa->id)
                    ->count();
                
                if ($otherSiswaCount === 0) {
                    // No other siswa, delete orang tua and user
                    if ($orangTua->user) {
                        $orangTua->user->delete();
                    }
                    $orangTua->delete();
                }
            }
        });
    }

    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class, 'orang_tua_id');
    }

    public function presensi()
    {
        return $this->hasMany(PresensiSiswa::class, 'siswa_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function nilai_perkembangans()
    {
        return $this->hasMany(NilaiPerkembangan::class, 'siswa_id');
    }
}
