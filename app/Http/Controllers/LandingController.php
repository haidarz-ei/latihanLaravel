<?php

namespace App\Http\Controllers;

use App\Models\LandingProgram;
use App\Models\LandingSetting;
use App\Models\LandingNavLink;
use App\Models\LandingFooterLink;
use Illuminate\Support\Facades\Cache;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // ambil setting sebagai array key-value
        $landing = Cache::remember('landing_settings', 60, function () {
            return LandingSetting::pluck('value', 'key')->toArray();
        });

        $programs = Cache::remember('landing_programs', 60, function () {
            return LandingProgram::where('status', 1)
            ->orderBy('position')
            ->get();
        });

        $navigation = Cache::remember('landing_navigation', 60, function () {
            return LandingNavLink::where('status', 1)
            ->orderBy('position')
            ->get();
        });

        $footer = Cache::remember('landing_footer_links', 60, function () {
            return LandingFooterLink::where('status', 1)
            ->orderBy('position')
            ->get();
        });

        return view('welcome', compact('landing', 'programs', 'navigation', 'footer'));
    }
}
