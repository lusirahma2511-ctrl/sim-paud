<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    protected $fillable = [
        'user_id', 'nama_ayah', 'nama_ibu', 'pekerjaan_ayah', 
        'pekerjaan_ibu', 'no_hp', 'alamat'
    ];

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'orang_tua_id');
    }

    // Relasi ke User (Akun Login)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
// ===================== ORANG TUA =====================


