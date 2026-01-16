<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mobil;
use Carbon\Carbon;

class ServisController extends Controller
{
    public function index()
    {
        $search = request('search');
        $query = Mobil::query();

        if ($search) {
            $query->where('nama_mobil', 'like', "%{$search}%")
                ->orWhere('plat_nomor', 'like', "%{$search}%"); // Pastikan plat_nomor sesuai kolom DB Anda
        }

        $mobils = $query->get();

        // Hitung total mobil yang perlu servis
        $totalPerluServis = $mobils->filter(function ($m) {
            if (!$m->tgl_servis_terakhir) return true;

            $interval = $m->interval_servis ?? 6;
            $nextService = Carbon::parse($m->tgl_servis_terakhir)->addMonths($interval);
            return now()->startOfDay()->greaterThanOrEqualTo($nextService->startOfDay());
        })->count();

        return view('admin.servis.index', compact('mobils', 'totalPerluServis', 'search'));
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi input (keterangan/catatan opsional)
        $request->validate([
            'tgl_servis_terakhir' => 'required|date|before_or_equal:today',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'tgl_servis_terakhir.required' => 'Tanggal servis harus diisi.',
            'tgl_servis_terakhir.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.'
        ]);

        try {
            $mobil = Mobil::findOrFail($id);

            // 2. Update data tanggal dan catatan
            // Pastikan kolom 'catatan_servis' sudah ada di database dan $fillable di Model Mobil
            $mobil->update([
                'tgl_servis_terakhir' => $request->tgl_servis_terakhir,
                'catatan_servis' => $request->keterangan, // Menyimpan isi textarea ke DB
            ]);

            return redirect()->back()->with('success', "Riwayat servis mobil {$mobil->nama_mobil} berhasil diperbarui!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }
}
