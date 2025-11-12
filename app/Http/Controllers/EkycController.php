<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EkycRegistration;
use Illuminate\Support\Facades\Auth;

class EkycController extends Controller
{
    public function step1()
    {
        // Ambil data draft user jika sudah ada
        $ekyc = EkycRegistration::where('user_id', Auth::id())
                // ->where('status', 'draft')
                ->first();
        
        // Simpan Session agar bisa lanjut ke step berikutnya
        if ($ekyc) {
            session(['ekyc_id' => $ekyc->id]);
        }

        return view('ekyc.step1', compact('ekyc'));
    }

    // public function storeStep1(Request $request)
    // {
    //     $request->validate([
    //         'nama' => 'required|string|max:255',
    //         'nik' => 'required|string|max:20',
    //         'tanggal_lahir' => 'required|date',
    //         'alamat' => 'required|string',
    //     ]);

    //     $ekyc = EkycRegistration::updateOrCreate(
    //         [
    //             'id' => session('ekyc_id'),
    //             'user_id' => Auth::id()
    //         ],
    //         [
    //             'nama' => $request->nama,
    //             'nik' => $request->nik,
    //             'tanggal_lahir' => $request->tanggal_lahir,
    //             'alamat' => $request->alamat,
    //             'status' => 'draft'
    //         ]
    //     );

    //     // Simpan ID ke session
    //     session(['ekyc_id' => $ekyc->id]);

    //     return redirect()->route('ekyc.step2')->with('success', 'Data pribadi disimpan, lanjut ke langkah berikutnya.');
    // }
    public function storeStep1(Request $request)
    {
        // Ambil record eKYC user (jika ada)
        $ekyc = EkycRegistration::where('user_id', Auth::id())->first();

        // Jika sudah submitted → jangan simpan/update, arahkan ke step2
        if ($ekyc && $ekyc->status === 'submitted') {
            // optional: simpan id ke session supaya flow tetap tahu record ini
            session(['ekyc_id' => $ekyc->id]);

            return redirect()->route('ekyc.step2')
                            ->with('info', 'eKYC sudah dikirim — data tidak bisa diubah. Melanjutkan ke langkah berikutnya.');
        }

        // Kalau belum submitted → validasi + simpan seperti biasa
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
        ]);

        $ekyc = EkycRegistration::updateOrCreate(
            [
                'id' => session('ekyc_id'),
                'user_id' => Auth::id(),
            ],
            [
                'nama' => $request->nama,
                'nik' => $request->nik,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'status' => 'draft',
            ]
        );

        // Simpan ID ke session
        session(['ekyc_id' => $ekyc->id]);

        return redirect()->route('ekyc.step2')
                        ->with('success', 'Data pribadi disimpan, lanjut ke langkah berikutnya.');
    }

    
    public function step2()
    {
        $data = EkycRegistration::where('user_id', auth()->id())->first();
        return view('ekyc.step2', compact('data'));
    }

    public function storeStep2(Request $request)
    {
        // 🔹 Tambahan baru
        $ekyc = EkycRegistration::where('user_id', Auth::id())->first();

        if ($ekyc && $ekyc->status === 'submitted') {
            session(['ekyc_id' => $ekyc->id]);
            return redirect()->route('ekyc.step3')
                            ->with('info', 'eKYC sudah dikirim — data tidak bisa diubah. Melanjutkan ke langkah berikutnya.');
        }

        $validated = $request->validate([
            'file_ktp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'file_selfie' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

            $ekyc = EkycRegistration::firstOrCreate(['user_id' => auth()->id()]);

        if ($request->hasFile('file_ktp')) {
            $validated['file_ktp'] = $request->file('file_ktp')->store('ekyc', 'public');
        }

        if ($request->hasFile('file_selfie')) {
            $validated['file_selfie'] = $request->file('file_selfie')->store('ekyc', 'public');
        }

        $ekyc->update($validated);

        return redirect()->route('ekyc.step3')->with('success', 'Step 2 tersimpan.');
        // return back()->with('success', 'Step 2 tersimpan');
    
    }

    public function showStep3()
    {
        $data =  \App\Models\EkycRegistration::where('user_id', auth()->id())->first();
        return view('ekyc.step3', compact('data'));
    }

    public function storeStep3(Request $request)
    {
        // 🔹 Tambahan baru
        $ekyc = EkycRegistration::where('user_id', Auth::id())->first();

        if ($ekyc && $ekyc->status === 'submitted') {
            session(['ekyc_id' => $ekyc->id]);
            return redirect()->route('ekyc.step4')
                            ->with('info', 'eKYC sudah dikirim — data tidak bisa diubah. Melanjutkan ke langkah berikutnya.');
        }

        $request->validate([
            'asal_sd' => 'nullable|string|max:255',
            'asal_smp' => 'nullable|string|max:255',
            'asal_sma' => 'nullable|string|max:255',
            'file_kk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'file_ijazah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = \App\Models\EkycRegistration::where('user_id', auth()->id())->first();

        $data->asal_sd = $request->asal_sd;
        $data->asal_smp = $request->asal_smp;
        $data->asal_sma = $request->asal_sma;

        if ($request->hasFile('file_kk')) {
            $data->file_kk = $request->file('file_kk')->store('ekyc', 'public');
        }

        if ($request->hasFile('file_ijazah')) {
            $data->file_ijazah = $request->file('file_ijazah')->store('ekyc', 'public');
        }

        $data->save();

        return redirect()->route('ekyc.step4')->with('success', 'Data pendidikan berhasil disimpan, lanjut ke langkah berikutnya.');

    }

    public function showStep4()
    {
        $data = \App\Models\EkycRegistration::where('user_id', auth()->id())->first();

        // Ambil semua data alamat via model
        $alamatList = \App\Models\MasterAlamat::all();

        // Ambil provinsi unik untuk dropdown pertama
        $provinsiList = \App\Models\MasterAlamat::select('provinsi')->distinct()->pluck('provinsi');
        $kotaList = [];
        $kecamatanList = [];

        if ($data && $data->provinsi) {
            $kotaList = \App\Models\MasterAlamat::where('provinsi', $data->provinsi)
                ->select('kota')->distinct()->pluck('kota');
        }

        if ($data && $data->kota_kab) {
            $kecamatanList = \App\Models\MasterAlamat::where('kota', $data->kota_kab)
                ->select('kecamatan')->distinct()->pluck('kecamatan');
        }

        $sumberInformasi = ['sosmed', 'kerabat', 'informasi kampus'];

        return view('ekyc.step4', compact('data', 'alamatList', 'provinsiList', 'kotaList', 'kecamatanList', 'sumberInformasi'));
    }

    public function storeStep4(Request $request)
    {
        // 🔹 Tambahan baru
        $ekyc = EkycRegistration::where('user_id', Auth::id())->first();

        if ($ekyc && $ekyc->status === 'submitted') {
            session(['ekyc_id' => $ekyc->id]);
            return redirect()->route('ekyc.step5')
                            ->with('info', 'eKYC sudah dikirim — data tidak bisa diubah. Melanjutkan ke langkah berikutnya.');
        }

        $request->validate([
            'alamat_domisili' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:100',
            'kota_kab' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'nama_ibu' => 'nullable|string|max:100',
            'sumber_informasi' => 'nullable|string|max:100',
        ]);

        // Ambil data eKYC milik user login
        $data = \App\Models\EkycRegistration::where('user_id', auth()->id())->first();

        if (!$data) {
            return redirect()->route('ekyc.step4')->with('error', 'Data eKYC tidak ditemukan');
        }

        // Simpan data alamat & informasi pendaftaran
        $data->alamat_domisili = $request->alamat_domisili;
        $data->provinsi = $request->provinsi;
        $data->kota_kab = $request->kota_kab;
        $data->kecamatan = $request->kecamatan;
        $data->kode_pos = $request->kode_pos;
        $data->nama_ibu = $request->nama_ibu;
        $data->sumber_informasi = $request->sumber_informasi;
        $data->status = 'submitted';

        // $data->save();

        // return redirect()->route('dashboard')->with('success', 'eKYC berhasil diselesaikan!');

        $data->status = 'submitted';
        $data->save();

        // Arahkan ke halaman sukses (step 5)
        return redirect()->route('ekyc.step5')->with('success', 'Registrasi eKYC Anda telah selesai');
    }

    public function step5()
    {
        $data = EkycRegistration::where('user_id', auth()->id())->first();

        if (!$data) {
            return redirect()->route('ekyc.step1')->with('error', 'Data eKYC tidak ditemukan');
        }

        // kalau sudah diverifikasi admin
        if ($data->status === 'accepted' || $data->status === 'rejected') {
            return view('ekyc.status', ['status' => $data->status]);
        }

        // kalau belum selesai kirim data (masih proses submit)
        if ($data->status !== 'submitted') {
            return redirect()->route('ekyc.step4')->with('error', 'Lengkapi data terlebih dahulu sebelum menyelesaikan eKYC');
        }

        // tampilkan step5 normal
        return view('ekyc.step5', compact('data'));
    }


    public function showStatus()
    {
        $ekyc = \App\Models\EkycRegistration::where('user_id', auth()->id())->first();

        if (!$ekyc) {
            return redirect()->route('ekyc.step1');
        }

        $status = strtolower($ekyc->status);

        return view('ekyc.status', compact('status'));
    }

}


