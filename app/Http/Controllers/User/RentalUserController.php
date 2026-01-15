<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Mobil;
use App\Models\Penyewa;
use App\Models\Rental;
use App\Models\Invoice;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RentalUserController extends Controller
{
    public function create($id)
    {
        $mobil = Mobil::findOrFail($id);
        $karyawan = Karyawan::all();
        return view('user.rental.create', compact('mobil', 'karyawan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mobil_id'          => 'required|exists:mobils,id',
            'telp'              => 'required|string|max:15',
            'alamat'            => 'required|string',
            'pekerjaan'         => 'required|string',
            'foto_ktp'          => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal_mulai'     => 'required|date|after_or_equal:today',
            'lama_sewa'         => 'required|integer|min:1',
            'karyawan_id'   => 'required|exists:karyawans,id',
            'metode_pembayaran' => 'required|string',
        ]);

        $mobil = Mobil::findOrFail($request->mobil_id);
        $lamaSewa = (int) $request->lama_sewa;
        $total_harga = $mobil->harga_sewa * $lamaSewa;

        // Upload KTP
        $file = $request->file('foto_ktp');
        $nama_file = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/ktp'), $nama_file);

        DB::beginTransaction();
        try {
            // 1. Penyewa
            $penyewa = Penyewa::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'nama'      => Auth::user()->name,
                    'email'     => Auth::user()->email,
                    'no_telp'   => $request->telp,
                    'alamat'    => $request->alamat,
                    'pekerjaan' => $request->pekerjaan,
                    'foto_ktp'  => $nama_file,
                ]
            );

            // 2. Hitung tanggal kembali
            $tgl_kembali = Carbon::parse($request->tanggal_mulai)
                ->addDays($lamaSewa)
                ->toDateString();

            // 3. Rental (BELUM AKTIF)
            $rental = Rental::create([
                'penyewa_id'      => $penyewa->id,
                'mobil_id'        => $mobil->id,
                'karyawan_id' => $request->karyawan_id,
                'tgl_sewa'        => $request->tanggal_mulai,
                'tgl_kembali'     => $tgl_kembali,
                'lama_sewa'       => $lamaSewa,
                'total_harga'     => $total_harga,
                'dp'              => 0,
                'sisa_bayar'      => $total_harga,
                'denda'           => 0,
                'status'          => 'booking',
            ]);

            // 4. Invoice
            $invoice = Invoice::create([
                'rental_id'         => $rental->id,
                'kode_invoice'      => 'INV-' . now()->format('YmdHis'),
                'total_tagihan'     => $total_harga,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status'            => 'pending',
            ]);

            DB::commit();

            // 5. Redirect ke invoice
            return redirect()
                ->route('user.rental.invoice', $invoice->id)
                ->with('success', 'Silakan selesaikan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();

            if (file_exists(public_path('uploads/ktp/' . $nama_file))) {
                unlink(public_path('uploads/ktp/' . $nama_file));
            }

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // =======================
    // INVOICE USER
    // =======================

    public function invoiceShow($id)
    {
        // Tambahkan 'rental.karyawan' ke dalam eager loading eager loading
        $invoice = Invoice::with(['rental.mobil', 'rental.penyewa', 'rental.karyawan'])
            ->whereHas('rental.penyewa', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->findOrFail($id);

        return view('user.rental.invoice', compact('invoice'));
    }

    // TAMBAHKAN METHOD BARU UNTUK CETAK
    public function cetak($id)
    {
        $invoice = Invoice::with(['rental.mobil', 'rental.penyewa', 'rental.karyawan'])
            ->whereHas('rental.penyewa', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->findOrFail($id);

        return view('user.rental.cetak', compact('invoice'));
    }

    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $invoice = Invoice::findOrFail($id);

        $file = $request->file('bukti_bayar');
        $nama = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/bukti'), $nama);

        $invoice->update([
            'bukti_bayar' => $nama,
            'status' => 'menunggu_verifikasi'
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil dikirim.');
    }

    // =======================
    // RIWAYAT
    // =======================

    public function riwayatUser()
    {
        $riwayat = Rental::whereHas('penyewa', function ($q) {
            $q->where('user_id', Auth::id());
        })->with('mobil')->orderBy('created_at', 'DESC')->get();

        return view('user.rental.riwayat', compact('riwayat'));
    }

    // =======================
    // KEMBALIKAN MOBIL
    // =======================

    public function kembalikan($id)
    {
        $rental = Rental::whereHas('penyewa', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        if ($rental->status !== 'disewa') {
            return back()->with('error', 'Mobil belum dalam status disewa.');
        }

        $rental->update(['status' => 'selesai']);
        $rental->mobil->update(['status' => 'tersedia']);

        return back()->with('success', 'Mobil berhasil dikembalikan.');
    }
}
