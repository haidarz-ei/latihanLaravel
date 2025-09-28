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
        Matkul::create($request->only('namaMatkul', 'deskripsi'));
        return redirect()->back();
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

        return redirect()->route('matkul.matkul')->with('success', 'Data berhasil di update!');
    }

    // Delete
    public function destroy($id)
    {
        $matkul = Matkul::findOrFail($id);
        $matkul->delete();

        return redirect()->route('matkul.matkul')->with('success', 'Data berhasil dihapus!');
    }
}
