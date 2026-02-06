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
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $query = Rental::with(['penyewa', 'mobil', 'invoice'])
            ->whereMonth('tgl_sewa', $bulan)
            ->whereYear('tgl_sewa', $tahun);

        $rentals = $query->latest()->get();

        // Hitung Pendapatan: (Total Lunas dari Invoice) + (Total Denda dari Rental)
        $pendapatanInvoice = Invoice::where('status', 'lunas')
            ->whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->sum('jumlah_dibayar');

        $totalDenda = $rentals->sum('denda');
        $totalPendapatan = $pendapatanInvoice + $totalDenda;

        $totalTransaksi = $rentals->count();
        $transaksiSelesai = $rentals->where('status', 'selesai')->count();
        $transaksiAktif = $rentals->where('status', 'disewa')->count();

        return view('admin.laporan.index', compact(
            'rentals',
            'totalPendapatan',
            'totalTransaksi',
            'transaksiSelesai',
            'transaksiAktif',
            'totalDenda', // Kirim total denda ke view jika ingin ditampilkan terpisah
            'bulan',
            'tahun'
        ));
    }
}
