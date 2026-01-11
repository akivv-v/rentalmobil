<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mobil;
use App\Models\Penyewa;
use App\Models\Rental;
// Model Pembayaran tidak perlu di-import jika datanya sudah menyatu di Rental

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Utama
        $totalMobil = Mobil::count();
        $totalPenyewa = Penyewa::count();
        
        // Pendapatan diambil dari Rental yang statusnya sudah 'disewa' atau 'selesai'
        $pendapatan = Rental::whereIn('status', ['disewa', 'selesai'])->sum('total_harga');

        // 2. Data Terbaru untuk Tabel di Dashboard
        $mobilTerbaru = Mobil::latest()->take(3)->get();
        $penyewaTerbaru = Penyewa::latest()->take(3)->get();
        
        // Pemasukan terbaru diambil dari Rental terbaru yang sudah lunas/proses
        $pemasukanTerbaru = Rental::with(['mobil', 'penyewa'])
            ->whereIn('status', ['disewa', 'selesai'])
            ->latest()
            ->take(3)
            ->get();

        return view('admin.dashboard', compact(
            'totalMobil', 
            'totalPenyewa', 
            'pendapatan',
            'mobilTerbaru', 
            'penyewaTerbaru', 
            'pemasukanTerbaru'
        ));
    }
}