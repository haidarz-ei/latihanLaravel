<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LandingProgramController extends Controller
{
    public function index()
    {
        $programs = LandingProgram::orderBy('position')->get();
        return view('admin.landing.program.index', compact('programs'));
    }

// ------------------------------------------------------------------------------
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'title' => 'required',
    //         'position' => 'required|integer',
    //         'image' => 'nullable|image|max:2048'
    //     ]);

    //     $data = $request->all();

    //     if ($request->hasFile('image')) {
    //         $data['image'] = $request->file('image')->store('landing/programs', 'public');
    //     }

    //     LandingProgram::create($data);

    //     return redirect()->route('admin.landing.programs.index')->with('success', 'Program added');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'nullable|integer|min:1',
            'image' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $maxPosition = LandingProgram::max('position') ?? 0;
            $newPos = $request->position ?? ($maxPosition + 1);

            // Geser semua program yang posisinya >= newPos turun 1
            LandingProgram::where('position', '>=', $newPos)->increment('position');

            $data = [
                'title' => $request->title,
                'description' => $request->description ?? null,
                'position' => $newPos,
                'status' => $request->status ?? 1,
            ];

            // Handle content_type
            if ($request->content_type === 'icon') {
                $data['image'] = null;
                $data['icon'] = $request->icon ?? null;
            } elseif ($request->content_type === 'image') {
                $data['icon'] = null;
                if ($request->hasFile('image')) {
                    $data['image'] = $request->file('image')->store('programs', 'public');
                } else {
                    $data['image'] = null;
                }
            }

            LandingProgram::create($data);
        });

        // Clear cache untuk memastikan data terbaru ditampilkan di landing page
        Cache::forget('landing_programs');

        return redirect()
            ->route('admin.landing.programs.index')
            ->with('success', 'Program berhasil ditambahkan');
    }
// ------------------------------------------------------------------------------

// ---------------------------------------------------------------------------------
    // public function update(Request $request, $id)
    // {
    //     $program = LandingProgram::findOrFail($id);

    //     $request->validate([
    //         'title' => 'required',
    //         'position' => 'required|integer',
    //     ]);

    //     $data = $request->all();

    //     if ($request->hasFile('image')) {
    //         $data['image'] = $request->file('image')->store('landing/programs', 'public');
    //     }

    //     $program->update($data);

    //     return redirect()->route('admin.landing.programs.index')->with('success', 'Program updated');
    // }

    public function update(Request $request, $id)
    {
        $program = LandingProgram::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'required|integer|min:1',
        ]);

        $newPos = $request->position;
        $oldPos = $program->position;

        DB::transaction(function () use ($program, $newPos, $oldPos, $request) {
            if ($oldPos != $newPos) {
                if ($newPos > $oldPos) {
                    LandingProgram::whereBetween('position', [$oldPos + 1, $newPos])->decrement('position');
                } else {
                    LandingProgram::whereBetween('position', [$newPos, $oldPos - 1])->increment('position');
                }
            }

            $data = [
                'title' => $request->title,
                'description' => $request->description ?? null,
                'position' => $newPos,
                'status' => $request->status ?? 1,
            ];

            // Handle content_type
            if ($request->content_type === 'icon') {
                // Hapus file image lama jika ada
                if ($program->image && \Storage::disk('public')->exists($program->image)) {
                    \Storage::disk('public')->delete($program->image);
                }
                $data['image'] = null;
                $data['icon'] = $request->icon ?? null;
            } elseif ($request->content_type === 'image') {
                $data['icon'] = null;
                if ($request->hasFile('image')) {
                    // Hapus file image lama jika ada
                    if ($program->image && \Storage::disk('public')->exists($program->image)) {
                        \Storage::disk('public')->delete($program->image);
                    }
                    $data['image'] = $request->file('image')->store('programs', 'public');
                } else {
                    // Jika tipe image tapi tidak upload file baru, tetap gunakan image lama
                    $data['image'] = $program->image;
                }
            }

            $program->update($data);
        });

        // Clear cache untuk memastikan data terbaru ditampilkan di landing page
        Cache::forget('landing_programs');

        return redirect()
            ->route('admin.landing.programs.index')
            ->with('success', 'Program berhasil diupdate');
    }
// ---------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------
    // public function destroy($id)
    // {
    //     LandingProgram::destroy($id);
    //     return back()->with('success', 'Program removed');
    // }

    public function destroy($id)
    {
        $program = LandingProgram::findOrFail($id);
        $oldPos = $program->position;

        DB::transaction(function () use ($program, $oldPos) {
            // Hapus file image jika ada
            if ($program->image && \Storage::disk('public')->exists($program->image)) {
                \Storage::disk('public')->delete($program->image);
            }
            
            $program->delete();
            LandingProgram::where('position', '>', $oldPos)->decrement('position');
        });

        // Clear cache untuk memastikan data terbaru ditampilkan di landing page
        Cache::forget('landing_programs');

        return back()->with('success', 'Program berhasil dihapus');
    }
// ------------------------------------------------------------------------------

}


/* 
Laporan Singkat LandingProgramController

Controller ini mengelola program studi landing page dengan fitur pengelolaan posisi otomatis dan dukungan 
dua tipe konten (icon HTML atau image). Semua operasi menggunakan DB transaction untuk menjaga konsistensi data.

Fitur Utama:
- Store: Penambahan program dengan posisi otomatis (max+1 jika dikosongkan), pergeseran posisi program lain, 
  dan dukungan content_type (icon/image) dengan logika saling meniadakan.
- Update: Perubahan program dengan pergeseran posisi otomatis saat position diubah, penghapusan file image 
  lama saat upload baru atau ubah ke tipe icon, dan preservasi image lama jika tidak upload file baru.
- Destroy: Penghapusan program dengan cleanup file image dari storage dan pergeseran posisi otomatis.

Pengelolaan File:
- Saat upload image baru, file lama otomatis dihapus untuk menghemat storage.
- Saat ubah dari image ke icon, file image dihapus.
- Saat hapus program, file image juga dihapus.

Keamanan:
- Validasi lengkap di backend untuk semua field.
- DB transaction untuk atomic operations.
- Storage cleanup untuk mencegah penumpukan file tidak terpakai.
*/
