<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MobilController extends Controller
{
    public function index()
    {
        $mobil = Mobil::latest()->paginate(10);
        return view('admin.mobil.index', compact('mobil'));
    }

    public function create()
    {
        return view('admin.mobil.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mobil' => 'required',
            'merk' => 'nullable|string',
            'plat_nomor' => 'required',
            'tahun' => 'nullable|integer',
            'harga_sewa' => 'required|integer',
            'status' => 'required',
            'deskripsi' => 'nullable',
            'gambar' => 'image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('mobil', 'public');
        }

        Mobil::create($data);

        return redirect()->route('admin.mobil.index')->with('success', 'Mobil berhasil ditambahkan!');
    }

    public function show(Mobil $mobil)
    {
        return view('admin.mobil.show', compact('mobil'));
    }

    public function edit(Mobil $mobil)
    {
        return view('admin.mobil.edit', compact('mobil'));
    }

    public function update(Request $request, Mobil $mobil)
    {
        $request->validate([
            'nama_mobil' => 'required',
            'merk' => 'nullable|string',
            'plat_nomor' => 'required',
            'tahun' => 'nullable|integer',
            'harga_sewa' => 'required|integer',
            'status' => 'required',
            'deskripsi' => 'nullable',
            'gambar' => 'image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $data = $request->all();

        // Jika upload gambar baru
        if ($request->hasFile('gambar')) {
            if ($mobil->gambar) {
                Storage::disk('public')->delete($mobil->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('mobil', 'public');
        }

        $mobil->update($data);

        return redirect()->route('admin.mobil.index')->with('success', 'Data mobil berhasil diperbarui!');
    }

    public function destroy(Mobil $mobil)
    {
        if ($mobil->gambar) {
            Storage::disk('public')->delete($mobil->gambar);
        }

        $mobil->delete();
        return redirect()->route('admin.mobil.index')->with('success', 'Mobil berhasil dihapus!');
    }
}
