<?php

namespace App\Models;

use Carbon\Carbon;
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
        'denda',
        'status'
    ];

    protected $casts = [
        'denda' => 'float',
        'total_harga' => 'float',
    ];

    public function penyewa()
    {
        return $this->belongsTo(Penyewa::class);
    }

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

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function getDendaAttribute($value)
    {
        // Jika sudah selesai, gunakan nilai di database (pastikan tidak negatif)
        if ($this->status === 'selesai') {
            return ($value < 0) ? 0 : $value;
        }

        // Jika masih disewa, hitung denda berjalan
        if ($this->status === 'disewa') {
            $tglKembali = Carbon::parse($this->tgl_kembali)->startOfDay();
            $hariIni = Carbon::now()->startOfDay();

            if ($hariIni->gt($tglKembali)) {
                $selisihHari = $hariIni->diffInDays($tglKembali);
                return $selisihHari * 50000;
            }
        }

        return 0;
    }
}
