<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter bulan & tahun, default ke bulan sekarang
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        // Ambil data transaksi yang SELESAI atau DISEWA pada bulan/tahun terpilih
        $query = Rental::with(['penyewa', 'mobil', 'invoice'])
            ->whereMonth('tgl_sewa', $bulan)
            ->whereYear('tgl_sewa', $tahun);

        $rentals = $query->latest()->get();

        // Hitung Ringkasan
        $totalPendapatan = Invoice::where('status', 'lunas')
            ->whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->sum('jumlah_dibayar');

        $totalTransaksi = $rentals->count();
        $transaksiSelesai = $rentals->where('status', 'selesai')->count();
        $transaksiAktif = $rentals->where('status', 'disewa')->count();

        return view('admin.laporan.index', compact(
            'rentals', 
            'totalPendapatan', 
            'totalTransaksi', 
            'transaksiSelesai', 
            'transaksiAktif',
            'bulan',
            'tahun'
        ));
    }
}