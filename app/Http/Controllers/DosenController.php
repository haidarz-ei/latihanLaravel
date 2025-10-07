<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index()
    {
        $data = Dosen::all();
        return view('dosen.dosen', compact('data'));
    }

    // public function store(Request $request)
    // {
    //     Dosen::create($request->only('namaDosen', 'nid'));
    //     return redirect()->back();
    // }

        public function store(Request $request)
    {
        $request->validate([
            'namaDosen' => 'required',
            'nid' => 'required',
        ]);

        Dosen::create([
            'namaDosen' => $request->namaDosen,
            'nid' => $request->nid,
        ]);
        return redirect()->back()->with('success', 'Data Dosen berhasil ditambahkan');
    }

    // Edit
    public function edit($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('dosen.edit', compact('dosen'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'namaDosen' => 'required',
            'nid' => 'required',
        ]);

        $dosen = Dosen::findOrFail($id);
        $dosen->update($request->only('namaDosen', 'nid'));

        return redirect()->route('dosen.index')->with('success', 'Data berhasil di update!');
    }

    // Delete
    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();

        return redirect()->route('dosen.index')->with('error', 'Data berhasil dihapus!');
    }
}
