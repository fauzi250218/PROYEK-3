<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kelas', 'deskripsi', 'user_id'];

    public function murids()
    {
        return $this->hasMany(Murid::class, 'kelas_id');
    }

    public function wali()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
