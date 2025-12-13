<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingSetting;
use Illuminate\Http\Request;

class LandingSettingController extends Controller
{
    public function index()
    {
        $settings = LandingSetting::orderBy('key')->get();
        return view('admin.landing.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'key'    => 'required|string',
            'type'   => 'required|in:text,image,json',
            'status' => 'required|boolean',
            'value'  => 'nullable',
        ]);

        $value = $request->value;

        if ($request->type === 'image' && $request->hasFile('value')) {
            $value = $request->file('value')->store('landing', 'public');
        }

        LandingSetting::create([
            'key'    => $request->key,
            'type'   => $request->type,
            'value'  => $value,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Setting berhasil disimpan');
    }

    public function update(Request $request, $id)
    {
        $setting = LandingSetting::findOrFail($id);

        $request->validate([
            'type'  => 'required|in:text,image,json',
            'value' => 'nullable',
        ]);

        if ($request->type === 'image') {
            if ($request->hasFile('value')) {
                $setting->value = $request->file('value')->store('landing', 'public');
            }
        } else {
            $setting->value = $request->value;
        }

        $setting->type = $request->type;
        $setting->status = $request->status ?? 1;
        $setting->save();

        return redirect()->back()->with('success', 'Setting berhasil diupdate');
    }
}
