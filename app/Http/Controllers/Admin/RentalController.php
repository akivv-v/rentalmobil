<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Mobil;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RentalController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $rentals = Rental::with(['penyewa', 'mobil', 'invoice'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('penyewa', function ($qp) use ($search) {
                        $qp->where('nama', 'like', "%{$search}%");
                    })
                        ->orWhereHas('mobil', function ($qm) use ($search) {
                            $qm->where('nama_mobil', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.rental.index', compact('rentals'));
    }

    public function show($id)
    {
        $rental = Rental::with(['penyewa', 'mobil', 'invoice'])->findOrFail($id);
        return view('admin.rental.show', compact('rental'));
    }

    /**
     * FUNGSI UNTUK KONFIRMASI PEMBAYARAN (Baik Transfer maupun Kantor)
     * Digunakan dari halaman Detail maupun Tombol Aksi di Index
     */
    public function konfirmasiPembayaran(Request $request, $id)
    {
        // Cari rental atau invoice. Di sini kita asumsikan $id adalah ID Rental 
        // agar konsisten dengan route admin.rental.konfirmasi
        $rental = Rental::with(['mobil', 'invoice'])->findOrFail($id);

        if (!$rental->invoice) {
            return back()->with('error', 'Invoice tidak ditemukan.');
        }

        // Jika metode pembayaran adalah 'kantor' dan ada input nominal, validasi dulu
        if ($rental->invoice->metode_pembayaran === 'kantor' && $request->has('nominal')) {
            $request->validate([
                'nominal' => 'required|numeric|min:' . $rental->invoice->total_tagihan
            ], [
                'nominal.min' => 'Nominal yang dimasukkan harus lunas (Rp ' . number_format($rental->invoice->total_tagihan, 0, ',', '.') . ')'
            ]);
        }

        DB::beginTransaction();
        try {
            // 1. Update Invoice
            $rental->invoice->update([
                'status'         => 'lunas',
                'jumlah_dibayar' => $rental->invoice->total_tagihan,
                'tanggal_bayar'  => Carbon::now(),
            ]);

            // 2. Update Status Rental menjadi 'disewa'
            $rental->update([
                'status'     => 'disewa',
                'dp'         => $rental->invoice->total_tagihan,
                'sisa_bayar' => 0,
            ]);

            // 3. Update Status Mobil menjadi 'disewa'
            $rental->mobil->update([
                'status' => 'disewa'
            ]);

            DB::commit();
            return back()->with('success', 'Pembayaran berhasil dikonfirmasi. Unit kini dalam status DISEWA.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * FUNGSI KHUSUS UNTUK TOMBOL "BAYAR DI KANTOR" DI HALAMAN INDEX
     * Jika Anda mengirimkan ID Invoice langsung dari tombol cash di tabel
     */
    public function bayarDiKantor(Request $request, $id)
    {
        $invoice = Invoice::with('rental.mobil')->findOrFail($id);
        $rental = $invoice->rental;

        DB::beginTransaction();
        try {
            $invoice->update([
                'status'         => 'lunas',
                'jumlah_dibayar' => $invoice->total_tagihan,
                'tanggal_bayar'  => Carbon::now(),
            ]);

            $rental->update([
                'status'     => 'disewa',
                'dp'         => $invoice->total_tagihan,
                'sisa_bayar' => 0,
            ]);

            $rental->mobil->update([
                'status' => 'disewa'
            ]);

            DB::commit();
            return back()->with('success', 'Pembayaran tunai berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }

    public function setKembali($id)
    {
        $rental = Rental::with('mobil')->findOrFail($id);

        DB::beginTransaction();
        try {
            $rental->update(['status' => 'selesai']);

            // Kembalikan status mobil jadi tersedia
            $rental->mobil->update(['status' => 'tersedia']);

            DB::commit();
            return back()->with('success', 'Mobil berhasil dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pengembalian.');
        }
    }

    public function destroy($id)
    {
        $rental = Rental::with(['mobil', 'invoice'])->findOrFail($id);

        DB::beginTransaction();
        try {
            // Jika dihapus saat status masih disewa, bebaskan mobilnya dulu
            if ($rental->status === 'disewa' || $rental->status === 'booking') {
                $rental->mobil->update([
                    'status' => 'tersedia'
                ]);
            }

            if ($rental->invoice) {
                $rental->invoice->delete();
            }

            $rental->delete();

            DB::commit();
            return back()->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus transaksi.');
        }
    }
}
