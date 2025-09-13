<?php

namespace App\Http\Controllers;

use App\Models\Matkul;
use Illuminate\Http\Request;

class MatkulController extends Controller
{

    public function index()
    {
        $data = Ruangan::all(); 
        return view('mahasiswa.matkul', compact('data'));
    }

    public function store(Request $request)
    {
        Mahasiswa::create($request->only('namaMatkul', 'deskripsi'));
        return redirect()->back();
    }
}





