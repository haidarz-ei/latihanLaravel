<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index()
    {
        $data = Ruangan::all();
        return view('ruangan.ruangan', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'namaRuangan' => 'required',
            'kapasitas' => 'required',
        ]);

        Ruangan::create([
            'namaRuangan' => $request->namaRuangan,
            'kapasitas' => $request->kapasitas,
        ]);
        return redirect()->back()->with('success', 'Data Mata Kuliah berhasil ditambahkan');
    }

    // Edit
    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return view('ruangan.edit', compact('ruangan'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'namaRuangan' => 'required',
            'kapasitas' => 'required',
        ]);

        $ruangan = Ruangan::findOrFail($id);
        $ruangan->update($request->only('namaRuangan', 'kapasitas'));

        return redirect()->route('ruangan.index')->with('success', 'Data berhasil di update!');
    }

    // Delete
    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->delete();

        return redirect()->route('ruangan.index')->with('error', 'Data berhasil dihapus!');
    }
}
