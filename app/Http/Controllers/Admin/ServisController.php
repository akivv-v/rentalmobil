<?php

namespace App\Http\Controllers\Admin; // Harus ada '\Admin'

use App\Http\Controllers\Controller; // Tambahkan ini agar tidak error
use Illuminate\Http\Request;
use App\Models\Mobil;
use Carbon\Carbon;

class ServisController extends Controller
{
    public function index()
    {
        // Mengambil data mobil beserta pencarian jika dibutuhkan
        $search = request('search');
        
        $query = Mobil::query();

        if ($search) {
            $query->where('nama_mobil', 'like', "%{$search}%")
                  ->orWhere('nopol', 'like', "%{$search}%");
        }

        $mobils = $query->get();

        // Hitung berapa yang overdue (Perlu Servis)
        // Kita gunakan filter pada Collection
        $totalPerluServis = $mobils->filter(function ($m) {
            if (!$m->tgl_servis_terakhir) return true; // Anggap perlu servis jika belum pernah
            
            $nextService = Carbon::parse($m->tgl_servis_terakhir)->addMonths($m->interval_servis ?? 3);
            return now()->greaterThanOrEqualTo($nextService);
        })->count();

        return view('admin.servis.index', compact('mobils', 'totalPerluServis', 'search'));
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'tgl_servis_terakhir' => 'required|date|before_or_equal:today',
        ], [
            'tgl_servis_terakhir.required' => 'Tanggal servis harus diisi.',
            'tgl_servis_terakhir.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.'
        ]);

        try {
            $mobil = Mobil::findOrFail($id);
            
            // 2. Update data
            $mobil->update([
                'tgl_servis_terakhir' => $request->tgl_servis_terakhir,
            ]);

            return redirect()->back()->with('success', "Riwayat servis mobil {$mobil->nama_mobil} berhasil diperbarui!");
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }
}