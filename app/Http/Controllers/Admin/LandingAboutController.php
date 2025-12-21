<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LandingAbout;
use App\Models\LandingAboutDescription;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LandingAboutController extends Controller
{
    public function index()
    {
        $about = LandingAbout::first(); // Hanya ambil satu data about (edit only)
        $descriptions = LandingAboutDescription::orderBy('position')->get();
        return view('admin.landing.about.index', compact('about', 'descriptions'));
    }

    public function update(Request $request, $id)
    {
        $about = LandingAbout::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'paragraph_1' => 'required|string',
            'paragraph_2' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // Jika tidak ada file baru, pertahankan image lama
        if ($request->hasFile('image')) {
            if ($about->image) {
                Storage::disk('public')->delete($about->image);
            }
            $data['image'] = $request->file('image')->store('landing/about', 'public');
        } else {
            // Pertahankan image lama jika tidak ada file baru
            unset($data['image']);
        }

        $about->update($data);
        Cache::forget('landing_about');

        return redirect()->back()->with('success', 'About berhasil diperbarui.');
    }

    // ========== DESKRIPSI TENTANG ==========
    public function storeDescription(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string',
            'position' => 'nullable|integer',
        ]);

        // Auto set position jika tidak ada
        if (!isset($data['position'])) {
            $maxPosition = LandingAboutDescription::max('position') ?? 0;
            $data['position'] = $maxPosition + 1;
        }

        $data['status'] = 1;
        LandingAboutDescription::create($data);
        Cache::forget('landing_about_descriptions');

        return redirect()->back()->with('success', 'Deskripsi tentang berhasil ditambahkan.');
    }

    public function updateDescription(Request $request, $id)
    {
        $description = LandingAboutDescription::findOrFail($id);

        $data = $request->validate([
            'description' => 'required|string',
            'position' => 'nullable|integer',
        ]);

        $description->update($data);
        Cache::forget('landing_about_descriptions');

        return redirect()->back()->with('success', 'Deskripsi tentang berhasil diperbarui.');
    }

    public function destroyDescription($id)
    {
        $description = LandingAboutDescription::findOrFail($id);
        $description->delete();
        Cache::forget('landing_about_descriptions');

        return redirect()->back()->with('success', 'Deskripsi tentang berhasil dihapus.');
    }
}
