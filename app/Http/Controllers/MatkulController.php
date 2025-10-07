<?php

namespace App\Http\Controllers;

use App\Models\Matkul;
use Illuminate\Http\Request;

class MatkulController extends Controller
{
    public function index()
    {
        $data = Matkul::all();
        return view('matkul.matkul', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'namaMatkul' => 'required',
            'deskripsi' => 'required',
        ]);

        Matkul::create([
            'namaMatkul' => $request->namaMatkul,
            'deskripsi' => $request->deskripsi,
        ]);
        return redirect()->back()->with('success', 'Data Mata Kuliah berhasil ditambahkan');
    }

    // Edit
    public function edit($id)
    {
        $matkul = Matkul::findOrFail($id);
        return view('matkul.edit', compact('matkul'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'namaMatkul' => 'required',
            'deskripsi' => 'required',
        ]);

        $matkul = Matkul::findOrFail($id);
        $matkul->update($request->only('namaMatkul', 'deskripsi'));

        return redirect()->route('matkul.index')->with('success', 'Data berhasil di update!');
    }

    // Delete
    public function destroy($id)
    {
        $matkul = Matkul::findOrFail($id);
        $matkul->delete();

        return redirect()->route('matkul.index')->with('error', 'Data berhasil dihapus!');
    }
}
