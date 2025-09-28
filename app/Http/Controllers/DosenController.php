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

    public function store(Request $request)
    {
        Dosen::create($request->only('namaDosen', 'nid'));
        return redirect()->back();
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

        return redirect()->route('dosen.dosen')->with('success', 'Data berhasil di update!');
    }

    // Delete
    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();

        return redirect()->route('dosen.dosen')->with('success', 'Data berhasil dihapus!');
    }
}
