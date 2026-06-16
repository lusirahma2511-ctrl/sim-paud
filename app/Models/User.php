<?php

// app/Models/User.php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'status',
        'foto',
        'is_default_password',
    ];

    public function siswa()
{
    return $this->hasOne(Siswa::class, 'nisn', 'username');
}

public function guru()
    {
        return $this->hasOne(\App\Models\Guru::class);
    }

    public function orangtua()
    {
        return $this->hasOne(\App\Models\OrangTua::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}