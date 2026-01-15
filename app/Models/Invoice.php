<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'rental_id',
        'kode_invoice',
        'total_tagihan',
        'metode_pembayaran',
        'jumlah_dibayar',
        'bukti_bayar',
        'status',
        'tanggal_bayar'
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
