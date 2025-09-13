<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{

    public function index()
    {
        $data = Ruangan::all(); 
        return view('mahasiswa.ruangan', compact('data'));
    }

    public function store(Request $request)
    {
        Mahasiswa::create($request->only('namaRuangan', 'kapasitas'));
        return redirect()->back();
    }
}





