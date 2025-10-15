<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** 🔹 Relasi: satu user (guru) punya satu kelas binaan */
    public function kelas()
    {
        return $this->hasOne(Kelas::class, 'user_id');
    }

    /** 🔹 Relasi: satu user juga punya detail guru */
    public function guru()
    {
        return $this->hasOne(Guru::class, 'user_id');
    }

    /** 🔹 Helper: cek role user */
    public function isGuru()
    {
        return $this->role === 'guru';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
