<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $karyawans = Karyawan::when($search, function ($q) use ($search) {
            $q->where('nama', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%")
              ->orWhere('no_telp', 'like', "%$search%");
        })->paginate(6);

        return view('admin.karyawan.index', compact('karyawans', 'search'));
    }

    public function create()
    {
        return view('admin.karyawan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:karyawans',
            'no_telp' => 'required',
        ]);

        Karyawan::create($request->all());

        return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan berhasil ditambahkan');
    }

    public function edit(Karyawan $karyawan)
    {
        return view('admin.karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:karyawans,email,'.$karyawan->id,
            'no_telp' => 'required',
        ]);

        $karyawan->update($request->all());

        return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan berhasil diperbarui');
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return back()->with('success', 'Data karyawan berhasil dihapus');
    }
}
