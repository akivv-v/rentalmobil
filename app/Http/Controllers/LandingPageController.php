<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class LandingPageController extends Controller
{
    public function index(): RedirectResponse|View
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('user.dashboard');
        }

        // PERBAIKAN: Hapus filter 'where', ambil semua data mobil
        // Kita gunakan latest() supaya mobil terbaru muncul di atas
        // take(6) tetap dipertahankan agar dashboard tidak terlalu panjang
        $mobils = Mobil::latest()->take(6)->get();

        // AMBIL DATA ULASAN
        $ulasans = class_exists('\App\Models\Ulasan')
            ? Ulasan::with('user')->latest()->take(3)->get()
            : [];

        // KIRIM VARIABEL KE VIEW
        return view('welcome', compact('mobils', 'ulasans'));
    }
}
