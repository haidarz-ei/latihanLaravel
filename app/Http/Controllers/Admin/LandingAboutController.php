<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LandingAbout;
use Illuminate\Support\Facades\Storage;

class LandingAboutController extends Controller
{
    public function index()
    {
        $abouts = LandingAbout::all();
        return view('admin.landing.about.index', compact('abouts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'paragraph_1' => 'required|string',
            'paragraph_2' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('landing/about', 'public');
        }

        LandingAbout::create($data);

        return redirect()->back()->with('success', 'About berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $about = LandingAbout::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'paragraph_1' => 'required|string',
            'paragraph_2' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($about->image) {
                Storage::disk('public')->delete($about->image);
            }
            $data['image'] = $request->file('image')->store('landing/about', 'public');
        }

        $about->update($data);

        return redirect()->back()->with('success', 'About berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $about = LandingAbout::findOrFail($id);
        if ($about->image) {
            Storage::disk('public')->delete($about->image);
        }
        $about->delete();

        return redirect()->back()->with('success', 'About berhasil dihapus.');
    }
}
