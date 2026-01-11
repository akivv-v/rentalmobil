<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = [
        'penyewa_id',
        'mobil_id',
        'karyawan_id',
        'tgl_sewa',
        'tgl_kembali',
        'lama_sewa',
        'total_harga',
        'dp',
        'sisa_bayar',
        'denda',
        'status'
    ];

    public function penyewa()
    {
        return $this->belongsTo(Penyewa::class);
    }

    // app/Models/Rental.php

    public function karyawan()
    {
        // Parameter kedua adalah nama kolom foreign key di tabel rentals
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }
}
