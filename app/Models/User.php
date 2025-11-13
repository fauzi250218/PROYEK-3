<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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

    /**
     * Relasi ke model Guru (1 user = 1 guru)
     */
    public function guru()
    {
        return $this->hasOne(\App\Models\Guru::class, 'user_id');
    }

    /**
     * Relasi ke model Kelas (1 guru = 1 kelas binaan)
     */
    public function kelas()
    {
        return $this->hasOne(\App\Models\Kelas::class, 'guru_id');
    }

    /**
     * Cek apakah user adalah guru
     */
    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
