<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Nilai;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'user_id',
        'jenis_kelamin',
        'nomer_whatsapp',
        'mata_pelajaran',
        'foto_profil',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelasBinaan()
    {
        return $this->hasOne(Kelas::class, 'guru_id');
    }

    public function kelas()
    {
        return $this->kelasBinaan();
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'guru_id');
    }

    public function getNamaLengkapAttribute()
    {
        return $this->user->name ?? '-';
    }
}
