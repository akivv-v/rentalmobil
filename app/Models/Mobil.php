<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    protected $fillable = [
        'nama_mobil',
        'merk',
        'plat_nomor',
        'tahun',
        'harga_sewa',
        'status',
        'gambar',
        'deskripsi'
    ];

    /**
     * Relasi ke tabel rentals
     * Satu mobil bisa memiliki banyak data rental (riwayat sewa)
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class, 'mobil_id');
    }
}