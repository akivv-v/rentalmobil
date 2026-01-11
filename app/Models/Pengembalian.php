<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $fillable = [
        'rental_id',
        'tgl_kembali',
        'kondisi_mobil',
        'denda',
        'total_bayar'
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
