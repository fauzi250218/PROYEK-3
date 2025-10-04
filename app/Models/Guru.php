<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'nama',
        'email',
        'kelas',
        'jenis_kelamin',
        'kata_sandi',
        'nomer_whatsapp',
        'mata_pelajaran',
    ];

    // kalau mau otomatis hash password sebelum simpan
    public function setKataSandiAttribute($value) {
        $this->attributes['kata_sandi'] = bcrypt($value);
    }
}
