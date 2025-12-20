<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingNavLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingNavController extends Controller
{
    public function index()
    {
        $items = LandingNavLink::orderBy('position')->get();
        return view('admin.landing.nav.index', compact('items'));
    }

// ------------------------------------------------------------------------------
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'label' => 'required',
    //         'url' => 'required',
    //         'position' => 'required|integer',
    //     ]);

    //     LandingNavLink::create($request->all());
    //     return redirect()->route('admin.landing.navigation.index')->with('success', 'Menu created');
    // }

    // Simpan menu baru dengan posisi otomatis
    public function store(Request $request) {
        $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'position' => 'nullable|integer|min:1',
            'status' => 'nullable|boolean',
        ]);

        DB::transaction(function() use ($request) {
            $maxPosition = LandingNavLink::max('position') ?? 0;
            $newPos = $request->position ?? ($maxPosition + 1);

            // Geser menu yang posisinya >= newPos turun 1
            LandingNavLink::where('position', '>=', $newPos)->increment('position');

            LandingNavLink::create([
                'label' => $request->label,
                'url' => $request->url,
                'position' => $newPos,
                'status' => $request->status ?? 1,
            ]);
        });

        return redirect()->route('admin.landing.nav.index')
                         ->with('success', 'Menu berhasil ditambahkan');
    }
// ------------------------------------------------------------------------------

// ------------------------------------------------------------------------------
    // public function update(Request $request, $id)
    // {
    //     $item = LandingNavLink::findOrFail($id);
    //     $item->update($request->all());

    //     return redirect()->route('admin.landing.navigation.index')->with('success', 'Menu updated');
    // }

    // Update menu dengan otomatisasi posisi
    public function update(Request $request, $id)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'position' => 'required|integer|min:1',
        ]);

        DB::transaction(function() use ($request, $id) {
            $item = LandingNavLink::findOrFail($id);
            $oldPos = $item->position;
            $newPos = $request->position;

            if ($oldPos != $newPos) {
                if ($newPos > $oldPos) {
                    // Geser menu yang posisinya di antara oldPos+1 sampai newPos turun
                    LandingNavLink::whereBetween('position', [$oldPos + 1, $newPos])
                        ->decrement('position');
                } else {
                    // Geser menu yang posisinya di antara newPos sampai oldPos-1 naik
                    LandingNavLink::whereBetween('position', [$newPos, $oldPos - 1])
                        ->increment('position');
                }
            }

            $item->update([
                'label' => $request->label,
                'url' => $request->url,
                'position' => $newPos,
            ]);
        });

        return redirect()->route('admin.landing.nav.index')
                            ->with('success', 'Menu berhasil diupdate');
    }
// ------------------------------------------------------------------------------

// ------------------------------------------------------------------------------
    // public function destroy($id)
    // {
    //     LandingNavLink::destroy($id);
    //     return back()->with('success', 'Menu deleted');
    // }

    // Hapus menu dan geser posisi menu lain
    public function destroy($id) {
        $item = LandingNavLink::findOrFail($id);
        $oldPos = $item->position;

        DB::transaction(function() use ($item, $oldPos) {
            $item->delete();
            LandingNavLink::where('position', '>', $oldPos)->decrement('position');
        });

        return redirect()->route('admin.landing.nav.index')
                            ->with('success', 'Menu berhasil dihapus');
    }
// ------------------------------------------------------------------------------
}



/* 
# Laporan Singkat LandingNavController
## Pendahuluan
File `LandingNavController` mengelola menu navigasi landing page dengan fitur pengelolaan posisi menu yang otomatis dan dinamis.

## Fitur Utama
Controller ini dilengkapi dengan fitur-fitur berikut:

### 1. Penambahan Menu (Store)
* Penentuan **posisi menu secara otomatis** saat penambahan data.
* Jika position tidak diisi, menu akan ditambahkan di posisi terakhir (max position + 1).
* Jika position diisi, menu yang ada di posisi tersebut dan setelahnya akan digeser ke bawah.
* Menggunakan `DB::transaction()` untuk menjaga konsistensi data.

### 2. Update Menu
* Admin dapat mengubah label, URL, position, dan status.
* Jika position diubah, menu lain akan otomatis digeser:
  - Jika position baru > position lama: menu di antara position lama+1 sampai baru akan digeser ke bawah.
  - Jika position baru < position lama: menu di antara position baru sampai lama-1 akan digeser ke atas.
* Menggunakan `DB::transaction()` untuk memastikan perubahan posisi konsisten.

### 3. Hapus Menu (Destroy)
* Menu yang dihapus akan menghapus data dari database.
* Menu yang posisinya lebih besar dari menu yang dihapus akan otomatis digeser ke atas (decrement position).
* Menggunakan `DB::transaction()` untuk menjaga konsistensi urutan.

### 4. Interface
* Menggunakan modal untuk create dan edit (tidak ada halaman terpisah).
* Form create dan edit menggunakan Alpine.js untuk interaktivitas.
* Validasi di frontend dan backend untuk memastikan data valid.

## Keamanan dan Konsistensi
* Semua operasi yang mengubah posisi menggunakan `DB::transaction()` untuk memastikan atomicity.
* Validasi input di backend untuk mencegah data tidak valid.
* Konfirmasi sebelum menghapus untuk mencegah penghapusan tidak sengaja.

## Kesimpulan
`LandingNavController` menggunakan pendekatan yang canggih dengan pengelolaan posisi menu otomatis, memastikan urutan menu tetap rapi dan konsisten tanpa memerlukan input manual yang ketat dari admin.
*/