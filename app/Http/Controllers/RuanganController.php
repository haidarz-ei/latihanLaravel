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
        Ruangan::create($request->only('namaRuangan', 'kapasitas'));
        return redirect()->back();
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

        return redirect()->route('ruangan.index')->with('success', 'Data berhasil dihapus!');
    }
}
