<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyewa extends Model
{
    protected $fillable = [
        'user_id',    // Tambahkan ini!
        'nama',
        'email',
        'no_telp',
        'alamat',
        'pekerjaan',
        'foto_ktp',
    ];

    // Jika kamu ingin membuat relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
