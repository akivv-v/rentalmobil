<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Mobil;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // ID user yang sedang login

        // Semua mobil yang tersedia
        $mobils = Mobil::all();

        // $totalMobil = Mobil::count();
        // $totalRentalUser = Rental::where('penyewa_id', $userId)->count();

        return view('user.dashboard', compact('mobils'));
    }
}
