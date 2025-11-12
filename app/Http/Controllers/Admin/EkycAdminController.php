<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EkycRegistration;
use Illuminate\Http\Request;

class EkycAdminController extends Controller
{
    // Tampilkan semua data eKYC
    public function index()
    {
        $list = EkycRegistration::with('user')->latest()->paginate(10);
        return view('admin.ekyc.index', compact('list'));
    }

    // Tampilkan detail satu pendaftar
    public function show($id)
    {
        $data = EkycRegistration::with('user')->findOrFail($id);
        return view('admin.ekyc.show', compact('data'));
    }

    // Proses verifikasi / ubah status
    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $data = EkycRegistration::findOrFail($id);
        $data->status = strtolower($request->status); // pastikan huruf kecil
        $data->save();

        return redirect()->route('admin.ekyc.index')
                        ->with('success', 'Status eKYC berhasil diperbarui.');
    }
}
