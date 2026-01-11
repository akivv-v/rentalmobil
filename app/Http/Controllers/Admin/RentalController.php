<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Mobil;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    /**
     * Menampilkan semua data transaksi yang masuk dari user.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $rentals = Rental::with(['penyewa', 'mobil'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('penyewa', function ($q) use ($search) {
                    $q->where('nama', 'LIKE', "%$search%");
                })
                    ->orWhereHas('mobil', function ($q) use ($search) {
                        $q->where('nama_mobil', 'LIKE', "%$search%");
                    });
            })
            ->latest()
            ->paginate(10);

        return view('admin.rental.index', compact('rentals'));
    }

    /**
     * Detail transaksi untuk melihat bukti bayar/rincian.
     */
    public function show($id)
    {
        $rental = Rental::with(['penyewa', 'mobil', 'pembayaran'])->findOrFail($id);
        return view('admin.rental.show', compact('rental'));
    }

    /**
     * PENGGANTI MENU PEMBAYARAN
     * Fungsi untuk konfirmasi uang masuk.
     */
    public function konfirmasiBayar($id)
    {
        $rental = Rental::findOrFail($id);

        if ($rental->status !== 'booking') {
            return redirect()->back()->with('error', 'Transaksi ini sudah dikonfirmasi sebelumnya.');
        }

        // 1. Ubah status rental menjadi disewa
        $rental->update(['status' => 'disewa']);

        // 2. PERBAIKAN DI SINI:
        // Pastikan 'tidak tersedia' adalah string. 
        // Jika database Anda menggunakan ENUM, pastikan tulisannya SAMA PERSIS (misal: 'tidak_tersedia' atau 'booked')
        $rental->mobil->update([
            'status' => 'disewa'
        ]);

        return redirect()->route('admin.rental.index')->with('success', 'Pembayaran diverifikasi. Mobil resmi disewa.');
    }

    /**
     * PENGGANTI MENU PENGEMBALIAN
     * Fungsi untuk menyelesaikan rental saat mobil kembali.
     */
    // Tambahkan ini di dalam RentalController Admin
    public function setKembali($id)
    {
        $rental = Rental::findOrFail($id);

        // 1. Update status rental jadi selesai
        $rental->update(['status' => 'selesai']);

        // 2. Update status mobil jadi tersedia lagi
        $rental->mobil->update(['status' => 'tersedia']);

        return redirect()->back()->with('success', 'Mobil telah berhasil dikembalikan!');
    }

    /**
     * Menghapus transaksi jika diperlukan.
     */
    public function destroy($id)
    {
        $rental = Rental::findOrFail($id);

        // Jika transaksi dibatalkan, pastikan mobil kembali tersedia jika sebelumnya berstatus disewa
        if ($rental->status == 'disewa') {
            $rental->mobil->update(['status' => 'tersedia']);
        }

        $rental->delete();

        return redirect()->route('admin.rental.index')->with('success', 'Data transaksi berhasil dihapus!');
    }

    /* Catatan: Method create, store, edit, dan update tidak digunakan 
       karena Admin hanya menerima data inputan sepenuhnya dari User.
    */
}
