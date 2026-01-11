<?php

namespace App\Http\Controllers;

use App\Models\Mobil;   // Import model Mobil
use App\Models\Ulasan;  // Import model Ulasan (jika ada)
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

        // AMBIL DATA DARI DATABASE
        $mobils = Mobil::where('status', 'tersedia')->latest()->take(6)->get();
        
        // AMBIL DATA ULASAN (Jika tabel ulasan belum ada, ganti [] agar tidak error)
        $ulasans = class_exists('\App\Models\Ulasan') 
                   ? Ulasan::with('user')->latest()->take(3)->get() 
                   : [];

        // KIRIM VARIABEL KE VIEW
        return view('welcome', compact('mobils', 'ulasans'));
    }
}