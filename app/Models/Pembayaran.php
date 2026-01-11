<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{

    protected $table = 'pembayaran';
    
    protected $fillable = [
        'rental_id',
        'total_harga',
        'dp',
        'sisa_bayar',
        'metode'
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
