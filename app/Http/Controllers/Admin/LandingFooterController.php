<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingFooterLink;
use App\Models\LandingSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class LandingFooterController extends Controller
{
    /**
     * Display a listing of the footer links.
     */
// ------------------------------------------------------------------------------
    // public function index()
    // {
    //     $links = LandingFooterLink::orderBy('position')->get();
    //     return view('admin.landing.footer.index', compact('links'));
    // }

    public function index() 
    {
        $links = LandingFooterLink::orderBy('position')->get();
    
        $footerBrand = \App\Models\LandingSetting::whereIn('key', [
            'footer_brand_title',
            'footer_brand_description',
            'footer_text'
        ])->pluck('value', 'key');
    
        return view('admin.landing.footer.index', compact('links', 'footerBrand'));
    }
// ------------------------------------------------------------------------------

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ================= FOOTER BRAND =================
    public function saveBrand(Request $request)
    {
        $request->validate([
            'footer_brand_title' => 'nullable|string|max:255',
            'footer_brand_description' => 'nullable|string',
            'footer_text' => 'nullable|string|max:500',
        ]);

        $data = [
            'footer_brand_title' => $request->footer_brand_title,
            'footer_brand_description' => $request->footer_brand_description,
            'footer_text' => $request->footer_text,
        ];

        foreach ($data as $key => $value) {
            LandingSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => 'text',
                    'status' => 1
                ]
            );
        }

        return redirect()->back()->with('success', 'Footer settings berhasil disimpan');
    }

    
    /**
     * Store new footer link.
     */
// ------------------------------------------------------------------------------
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'label' => 'required|string|max:100',
    //         'url'   => 'nullable|url|max:255',
    //         'status'=> 'required|boolean'
    //     ]);

    //     $position = LandingFooterLink::max('position') + 1;

    //     LandingFooterLink::create([
    //         'label'    => $request->label,
    //         'url'      => $request->url,
    //         'position' => $position,
    //         'status'   => $request->status,
    //     ]);

    //     return redirect()->route('admin.landing.footer.index')
    //         ->with('success', 'Footer link berhasil ditambahkan.');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'position' => 'nullable|integer|min:1',
            'status' => 'required|boolean',
        ]);

        DB::transaction(function() use ($request) {
            // Jika posisi tidak diisi, otomatis max+1
            $newPos = $request->position ?? (LandingFooterLink::max('position') + 1);

            // Geser semua link yang posisinya >= newPos turun
            LandingFooterLink::where('position', '>=', $newPos)
                ->increment('position');

            // Tambah link baru
            LandingFooterLink::create([
                'label' => $request->label,
                'url' => $request->url,
                'position' => $newPos,
                'status' => $request->status,
            ]);
        });

        return redirect()->route('admin.landing.footer.index')
            ->with('success', 'Footer link berhasil ditambahkan.');
    }
// ------------------------------------------------------------------------------

    /**
     * Update footer link.
     */
// ------------------------------------------------------------------------------
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'label'  => 'required|string|max:100',
    //         'url'    => 'nullable|max:255',
    //         'status' => 'required|boolean',
    //     ]);

    //     $footer = LandingFooterLink::findOrFail($id);
    //     $footer->update([
    //         'label'    => $request->label,
    //         'url'      => $request->url,
    //         'status'   => $request->status,
    //     ]);

    //     return redirect()->route('admin.landing.footer.index')
    //         ->with('success', 'Footer link berhasil diperbarui.');
    // }

    public function update(Request $request, $id)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'position' => 'required|integer|min:1',
            'status' => 'required|boolean',
        ]);

        DB::transaction(function() use ($request, $id) {
            $footer = LandingFooterLink::findOrFail($id);
            $oldPos = $footer->position;
            $newPos = $request->position;

            // 1. Jika posisi berubah
            if ($oldPos != $newPos) {
                if ($newPos > $oldPos) {
                    // Geser semua link yang posisinya di antara oldPos+1 dan newPos turun
                    LandingFooterLink::whereBetween('position', [$oldPos + 1, $newPos])
                        ->decrement('position');
                } else {
                    // Geser semua link yang posisinya di antara newPos dan oldPos-1 naik
                    LandingFooterLink::whereBetween('position', [$newPos, $oldPos - 1])
                        ->increment('position');
                }
            }

            // 2. Update link dengan data baru
            $footer->update([
                'label' => $request->label,
                'url' => $request->url,
                'position' => $newPos,
                'status' => $request->status,
            ]);
        });

        return redirect()->route('admin.landing.footer.index')
            ->with('success', 'Footer link berhasil diperbarui.');
    }
// ------------------------------------------------------------------------------

    /**
     * Delete footer link.
     */
    public function destroy($id)
    {
        $footer = LandingFooterLink::findOrFail($id);
        $oldPos = $footer->position;

        DB::transaction(function() use ($footer, $oldPos) {
            $footer->delete();
            LandingFooterLink::where('position', '>', $oldPos)->decrement('position');
        });

        return redirect()->route('admin.landing.footer.index')
            ->with('success', 'Footer link berhasil dihapus');
    }

    /**
     * Update sorting order (AJAX optional).
     */
    public function reorder(Request $request)
    {
        foreach ($request->order as $order) {
            LandingFooterLink::where('id', $order['id'])
                ->update(['position' => $order['position']]);
        }

        return response()->json(['message' => 'Urutan footer berhasil diperbarui']);
    }

    /**
     * Toggle active/inactive status (optional).
     */
    public function toggleStatus($id)
    {
        $footer = LandingFooterLink::findOrFail($id);
        $footer->status = !$footer->status;
        $footer->save();

        return redirect()->route('admin.landing.footer.index')
            ->with('success', 'Status footer berhasil diperbarui.');
    }
}


/* 
Laporan Singkat LandingFooterController

Controller ini mengelola footer landing page dengan dua bagian: Footer Settings (brand title, description, 
dan footer text) yang disimpan di LandingSetting, dan Footer Links (daftar link navigasi) dengan 
pengelolaan posisi otomatis.

Fitur Utama:
- Index: Mengambil data footer links dan footer settings (brand_title, brand_description, footer_text) 
  dari LandingSetting untuk ditampilkan di view.
- SaveBrand: Menyimpan atau update tiga footer settings (title, description, dan text) ke LandingSetting 
  menggunakan updateOrCreate untuk memastikan data selalu ada. Semua setting disimpan dengan type 'text' 
  dan status aktif.
- Store: Penambahan footer link dengan posisi otomatis (max+1 jika dikosongkan) dan pergeseran posisi 
  link lain menggunakan DB transaction.
- Update: Perubahan footer link dengan pergeseran posisi otomatis saat position diubah, menggunakan 
  logika yang sama seperti LandingNavController.
- Destroy: Penghapusan footer link dengan pergeseran posisi otomatis untuk link yang tersisa.
- Reorder: Fitur opsional untuk mengubah urutan link via AJAX.
- ToggleStatus: Fitur opsional untuk toggle status aktif/nonaktif link.

Pengelolaan Posisi:
- Semua operasi yang mengubah posisi menggunakan DB transaction untuk atomicity.
- Sistem otomatis menggeser posisi link lain saat ada perubahan untuk mencegah gap.

Integrasi dengan LandingSetting:
- Footer settings (footer_brand_title, footer_brand_description, footer_text) terintegrasi dengan 
  LandingSetting untuk kemudahan pengelolaan global settings.
- Data dapat di-edit baik dari halaman Footer maupun Settings tanpa duplikasi.
- Menggunakan updateOrCreate untuk memastikan data selalu tersedia meskipun belum pernah dibuat.
*/