<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Mobil;
use App\Models\Rental; // Pastikan Model Rental di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MobilController extends Controller
{
    public function index(Request $request)
    {
        // Ambil keyword dari input search
        $keyword = $request->input('search');

        // Filter pencarian mobil
        $mobils = Mobil::when($keyword, function ($query) use ($keyword) {
            $query->where('nama_mobil', 'LIKE', "%{$keyword}%")
                ->orWhere('merk', 'LIKE', "%{$keyword}%")
                ->orWhere('tahun', 'LIKE', "%{$keyword}%")
                ->orWhere('plat_nomor', 'LIKE', "%{$keyword}%");
        })->get();

        // LOGIKA BARU: Cek apakah user sedang login punya rental yang belum selesai
        // Status yang dianggap "sedang menyewa": 'menunggu', 'disetujui', atau 'berjalan'
        $hasActiveRental = Rental::whereHas('penyewa', function ($q) {
            $q->where('user_id', Auth::id());
        })
            ->whereIn('status', ['booking', 'disewa']) // Hanya status ini yang membatasi user
            ->exists();

        return view('user.mobil.index', compact('mobils', 'keyword', 'hasActiveRental'));
    }

    public function show($id)
    {
        $mobil = Mobil::findOrFail($id);
        return view('user.mobil.show', compact('mobil'));
    }
}
