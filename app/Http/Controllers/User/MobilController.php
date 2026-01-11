<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Mobil;
use Illuminate\Http\Request;

class MobilController extends Controller
{
    public function index(Request $request)
    {
        // Ambil keyword dari input search
        $keyword = $request->input('search');

        // Jika ada pencarian, lakukan filter
        $mobils = Mobil::when($keyword, function ($query) use ($keyword) {
            $query->where('nama_mobil', 'LIKE', "%{$keyword}%")
                  ->orWhere('merk', 'LIKE', "%{$keyword}%")
                  ->orWhere('tahun', 'LIKE', "%{$keyword}%")
                  ->orWhere('plat_nomor', 'LIKE', "%{$keyword}%");
        })->get();

        return view('user.mobil.index', compact('mobils', 'keyword'));
    }

    public function show($id)
    {
        // detail mobil
        $mobil = Mobil::findOrFail($id);
        return view('user.mobil.show', compact('mobil'));
    }
}
