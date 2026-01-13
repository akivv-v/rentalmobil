<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Mobil;
use App\Models\Penyewa;
use App\Models\Rental;
use App\Models\Pembayaran;
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
            'penanggungjawab'   => 'required|exists:karyawans,id',
            'metode_pembayaran' => 'required|string',
            'jumlah_bayar'      => 'required|numeric', // Nilai ini dikirim dari hidden input di view
        ]);

        $mobil = Mobil::findOrFail($request->mobil_id);
        $total_harga = $mobil->harga_sewa * $request->lama_sewa;

        // Proses Upload KTP
        $file = $request->file('foto_ktp');
        $nama_file = time() . "_" . $file->getClientOriginalName();
        $file->move(public_path('uploads/ktp'), $nama_file);

        DB::beginTransaction();
        try {
            // 1. Simpan/Update Data Penyewa
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

            $lamaSewa = (int) $request->lama_sewa;

            $tgl_kembali = Carbon::parse($request->tanggal_mulai)
                ->addDays($lamaSewa)
                ->toDateString();


            // 2. Simpan ke Tabel Rentals (Sistem Langsung Lunas)
            $rental = Rental::create([
                'penyewa_id'      => $penyewa->id,
                'mobil_id'        => $mobil->id,
                'penanggungjawab' => $request->penanggungjawab,
                'tgl_sewa'        => $request->tanggal_mulai,
                'tgl_kembali'     => $tgl_kembali,
                'lama_sewa'       => $request->lama_sewa,
                'total_harga'     => $total_harga,
                'dp'              => $total_harga, // DP diisi total harga karena lunas
                'sisa_bayar'      => 0,            // Selalu 0
                'denda'           => 0,
                'status'          => 'booking',
            ]);

            // 3. Simpan ke Tabel Pembayaran
            Pembayaran::create([
                'rental_id'   => $rental->id,
                'total_harga' => $total_harga,
                'dp'          => $total_harga,
                'sisa_bayar'  => 0,
                'metode'      => $request->metode_pembayaran,
            ]);

            // 4. Update Status Mobil
            $mobil->update(['status' => 'disewa']);

            DB::commit();
            return redirect()->route('user.riwayat')->with('success', 'Booking berhasil dilakukan secara tunai/lunas!');
        } catch (\Exception $e) {
            DB::rollback();
            if (file_exists(public_path('uploads/ktp/' . $nama_file))) {
                unlink(public_path('uploads/ktp/' . $nama_file));
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function riwayatUser()
    {
        $riwayat = Rental::whereHas('penyewa', function ($q) {
            $q->where('user_id', Auth::id());
        })->with('mobil')->orderBy('created_at', 'DESC')->get();

        return view('user.rental.riwayat', compact('riwayat'));
    }

    public function kembalikan($id)
{
    // Cari data rental milik user yang sedang login
    $rental = Rental::where('user_id', Auth::id())->findOrFail($id);
    
    if ($rental->status !== 'disewa') {
        return redirect()->back()->with('error', 'Mobil belum dalam status disewa.');
    }

    // 1. Update status rental
    $rental->update([
        'status' => 'selesai'
    ]);

    // 2. Update status mobil jadi tersedia kembali
    $rental->mobil->update([
        'status' => 'tersedia'
    ]);

    return redirect()->back()->with('success', 'Permintaan pengembalian mobil berhasil dikirim!');
}

    // Fungsi lunasi dihapus karena sistem sekarang wajib lunas di awal
}