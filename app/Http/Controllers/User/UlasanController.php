<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ulasan;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    public function index()
    {
        // Mengambil semua ulasan dengan relasi user saja (relasi mobil dihapus)
        $semua_ulasan = Ulasan::with('user')->latest()->get();

        // Variabel $mobils_pernah_disewa dihapus karena ulasan bersifat umum
        return view('user.ulasan.index', compact('semua_ulasan'));
    }

    public function store(Request $request)
    {
        // Validasi diperbarui: mobil_id dihapus
        $request->validate([
            'bintang'  => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|min:5'
        ]);

        // Cek apakah user sudah pernah memberi ulasan umum (opsional)
        // Jika ingin user hanya boleh memberi 1 ulasan umum seumur hidup:
        $sudahUlasan = Ulasan::where('user_id', Auth::id())
                             ->whereNull('mobil_id') // Mencari ulasan yang tidak terikat mobil
                             ->exists();

        if ($sudahUlasan) {
            return back()->with('error', 'Anda sudah pernah memberikan ulasan layanan.');
        }

        Ulasan::create([
            'user_id'  => Auth::id(),
            'mobil_id' => null, // Set null karena ini ulasan umum
            'bintang'  => $request->bintang,
            'komentar' => $request->komentar,
        ]);

        return back()->with('success', 'Terima kasih atas testimoni Anda!');
    }
}