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
        return view('mahasiswa.index', compact('data', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nim' => 'required',
            'alamat' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        Mahasiswa::create([
            'nama' => $request->nama,
            'nim' => $request->nim,
            'alamat' => $request->alamat,
            'kelas_id' => $request->kelas_id,
        ]);
        
        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan');
    }

    // create
    public function create()
    {
        $kelas = Kelas::all(); 
        return view('mahasiswa.create', compact('kelas'));
    }

    // Edit
    public function edit($id)
    {
        $mhs = Mahasiswa::findOrFail($id);
        $kelas = Kelas::all(); 
        return view('mahasiswa.edit', compact('mhs', 'kelas'));
    }


    // Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'nim' => 'required',
            'alamat' => 'required',
        ]);

        $mhs = Mahasiswa::findOrFail($id);
        $mhs->update($request->only('nama', 'nim', 'alamat', 'kelas_id'));

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
