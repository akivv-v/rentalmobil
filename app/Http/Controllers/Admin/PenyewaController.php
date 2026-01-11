<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenyewaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $penyewas = Penyewa::when($search, function ($query) use ($search) {
            $query->where('nama', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('no_telp', 'like', "%$search%");
        })
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->appends(['search' => $search]); 

        return view('admin.penyewa.index', compact('penyewas', 'search'));
    }


    public function create()
    {
        return view('admin.penyewa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:penyewas,email',
            'no_telp' => 'required',
            'pekerjaan' => 'nullable',
            'alamat' => 'nullable',
            'foto_ktp' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('foto_ktp')) {
            $data['foto_ktp'] = $request->file('foto_ktp')->store('penyewa/ktp', 'public');
        }

        Penyewa::create($data);

        return redirect()->route('admin.penyewa.index')->with('success', 'Data penyewa berhasil ditambahkan');
    }

    public function edit(Penyewa $penyewa)
    {
        return view('admin.penyewa.edit', compact('penyewa'));
    }

    public function update(Request $request, Penyewa $penyewa)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:penyewas,email,' . $penyewa->id,
            'no_telp' => 'required',
            'pekerjaan' => 'nullable',
            'alamat' => 'nullable',
            'foto_ktp' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('foto_ktp')) {
            if ($penyewa->foto_ktp) {
                Storage::disk('public')->delete($penyewa->foto_ktp);
            }
            $data['foto_ktp'] = $request->file('foto_ktp')->store('penyewa/ktp', 'public');
        }

        $penyewa->update($data);

        return redirect()->route('admin.penyewa.index')->with('success', 'Data penyewa berhasil diperbarui');
    }

    public function destroy(Penyewa $penyewa)
    {
        if ($penyewa->foto_ktp) {
            Storage::disk('public')->delete($penyewa->foto_ktp);
        }

        $penyewa->delete();
        return redirect()->route('admin.penyewa.index')->with('success', 'Data penyewa berhasil dihapus');
    }
}
