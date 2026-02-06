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
                    // Cari di nama penyewa
                    $q->whereHas('penyewa', function ($qp) use ($search) {
                        $qp->where('nama', 'like', "%{$search}%");
                    })
                        // Atau cari di nama mobil
                        ->orWhereHas('mobil', function ($qm) use ($search) {
                            $qm->where('nama_mobil', 'like', "%{$search}%");
                        })
                        // Atau cari di status
                        ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc') // Lebih pasti daripada latest() jika ada ambiguity
            ->paginate(10)
            ->withQueryString();

        return view('admin.rental.index', compact('rentals'));
    }

    public function show($id)
    {
        $rental = Rental::with(['penyewa', 'mobil', 'invoice'])->findOrFail($id);
        return view('admin.rental.show', compact('rental'));
    }

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
        // Menggunakan eager loading agar performa cepat
        $rental = Rental::with('mobil')->findOrFail($id);

        // --- LOGIKA PENYELARASAN WAKTU ---
        // Paksa semua waktu ke jam 00:00:00 agar murni menghitung TANGGAL
        $tglKembali = \Carbon\Carbon::parse($rental->tgl_kembali)->startOfDay();
        $hariIni = \Carbon\Carbon::now()->startOfDay();

        DB::beginTransaction();
        try {
            $dendaFinal = 0;

            // Cek jika hari ini sudah melewati tanggal kembali
            if ($hariIni->gt($tglKembali)) {
                $selisihHari = $hariIni->diffInDays($tglKembali);
                $tarifDenda = 50000; 
                $dendaFinal = $selisihHari * $tarifDenda;
            }

            // Update data rental
            $rental->update([
                'status' => 'selesai',
                'denda'  => $dendaFinal, 
            ]);

            // Kembalikan status mobil
            $rental->mobil->update(['status' => 'tersedia']);

            DB::commit();

            // Pesan sukses yang dinamis
            if ($dendaFinal > 0) {
                return back()->with('success', "Mobil kembali. Terlambat " . $hariIni->diffInDays($tglKembali) . " hari. Denda dicatatkan: Rp " . number_format($dendaFinal, 0, ',', '.'));
            }

            return back()->with('success', "Mobil kembali tepat waktu.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
