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
    $tgl_mulai = $request->get('tgl_mulai');
    $tgl_selesai = $request->get('tgl_selesai');
    $bulan = (int) $request->get('bulan', date('m'));
    $tahun = (int) $request->get('tahun', date('Y'));

    $query = Rental::with(['penyewa', 'mobil', 'invoice']);

    // Logika Filter: Jika ada range tanggal, gunakan range. Jika tidak, gunakan bulan/tahun.
    if ($tgl_mulai && $tgl_selesai) {
        $query->whereBetween('tgl_sewa', [$tgl_mulai, $tgl_selesai]);
    } else {
        $query->whereMonth('tgl_sewa', $bulan)
            ->whereYear('tgl_sewa', $tahun);
    }

    $rentals = $query->latest()->get();

    // Hitung Pendapatan
    $invQuery = Invoice::where('status', 'lunas');
    if ($tgl_mulai && $tgl_selesai) {
        $invQuery->whereBetween('tanggal_bayar', [$tgl_mulai, $tgl_selesai]);
    } else {
        $invQuery->whereMonth('tanggal_bayar', $bulan)->whereYear('tanggal_bayar', $tahun);
    }
    
    $pendapatanInvoice = $invQuery->sum('jumlah_dibayar');
    $totalDenda = $rentals->sum('denda');
    $totalPendapatan = $pendapatanInvoice + $totalDenda;

    $totalTransaksi = $rentals->count();
    $transaksiSelesai = $rentals->where('status', 'selesai')->count();
    $transaksiAktif = $rentals->where('status', 'disewa')->count();

    return view('admin.laporan.index', compact(
        'rentals', 'totalPendapatan', 'totalTransaksi', 
        'transaksiSelesai', 'transaksiAktif', 'totalDenda', 
        'bulan', 'tahun', 'tgl_mulai', 'tgl_selesai'
    ));
}
}