<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Murid extends Model
{
    use HasFactory;

    protected $table = 'murids';

    protected $fillable = [
        'nis',
        'nama',
        'email',
        'kelas',
        'jenis_kelamin',
        'kata_sandi',
        'nomer_whatsapp',
    ];

    // Jika nama kolom di tabel kamu adalah "kelas_id" (relasi ke tabel kelas)
    // dan bukan kolom string seperti "kelas" (7A, 8B, dst),
    // maka ubah baris 'kelas' di atas jadi 'kelas_id', dan sesuaikan relasinya:
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
