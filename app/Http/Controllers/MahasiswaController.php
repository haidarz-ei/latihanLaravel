<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        // $data = Mahasiswa::all();
        // return view('mahasiswa.mahasiswa', compact('data'));

        $data = Mahasiswa::with('kelas')->get();
        $kelas = Kelas::all();
        return view('mahasiswa.mahasiswa', compact('data', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nim' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        Mahasiswa::create([
            'nama' => $request->nama,
            'nim' => $request->nim,
            'kelas_id' => $request->kelas_id,
        ]);
        return redirect()->back()->with('success', 'Data mahasiswa berhasil ditambahkan');
    }

    // Edit
    public function edit($id)
    {
        $mhs = Mahasiswa::findOrFail($id);
        return view('mahasiswa.edit', compact('mhs'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'nim' => 'required',
        ]);

        $mhs = Mahasiswa::findOrFail($id);
        $mhs->update($request->only('nama', 'nim', 'kelas_id'));

        return redirect()->route('mahasiswa.index')->with('success', 'Data berhasil di update!');
    }

    // Delete
    public function destroy($id)
    {
        $mhs = Mahasiswa::findOrFail($id);
        $mhs->delete();

        return redirect()->route('mahasiswa.index')->with('error', 'Data berhasil dihapus!');
    }
}
