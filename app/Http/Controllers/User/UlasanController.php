<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ulasan;
use App\Models\Mobil;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    public function index()
    {
        // Mengambil semua ulasan dengan relasi user dan mobil
        $semua_ulasan = Ulasan::with(['user', 'mobil'])->latest()->get();

        // Ambil mobil yang pernah disewa user ini untuk dropdown ulasan
        // Memastikan user hanya bisa mengulas mobil yang benar-benar pernah mereka sewa
        $mobils_pernah_disewa = Mobil::whereHas('rentals', function ($q) {
            $q->whereHas('penyewa', function ($query) {
                $query->where('user_id', Auth::id());
            });
        })->get();

        return view('user.ulasan.index', compact('semua_ulasan', 'mobils_pernah_disewa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mobil_id' => 'required|exists:mobils,id',
            'bintang'  => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|min:5'
        ]);

        // Cek apakah user sudah pernah memberi ulasan untuk mobil ini agar tidak spam
        $sudahUlasan = Ulasan::where('user_id', Auth::id())
                             ->where('mobil_id', $request->mobil_id)
                             ->exists();

        if ($sudahUlasan) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk mobil ini.');
        }

        Ulasan::create([
            'user_id'  => Auth::id(),
            'mobil_id' => $request->mobil_id,
            'bintang'  => $request->bintang,
            'komentar' => $request->komentar,
            // 'balasan_admin' dikosongkan karena baru dibuat
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}