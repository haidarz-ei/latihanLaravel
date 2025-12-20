<?php

namespace App\Http\Controllers;

use App\Models\EkycRegistration;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Matkul;
use App\Models\LandingProgram;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Jika admin, tampilkan dashboard admin
        if (auth()->user()->role === 'admin') {
            // Statistik untuk admin
            $stats = [
                'total_mahasiswa' => Mahasiswa::count(),
                'ekyc_pending' => EkycRegistration::where('status', 'submitted')->count(),
                'ekyc_accepted' => EkycRegistration::where('status', 'accepted')->count(),
                'ekyc_rejected' => EkycRegistration::where('status', 'rejected')->count(),
                'total_dosen' => Dosen::count(),
                'total_program' => LandingProgram::where('status', 1)->count(),
                'total_users' => User::where('role', 'user')->count(),
                'total_matkul' => Matkul::count(),
            ];

            // eKYC pending terbaru (5 terakhir)
            $recent_ekyc = EkycRegistration::where('status', 'submitted')
                ->with('user')
                ->latest()
                ->take(5)
                ->get();

            // eKYC by status untuk chart
            $ekyc_by_status = [
                'draft' => EkycRegistration::where('status', 'draft')->count(),
                'submitted' => EkycRegistration::where('status', 'submitted')->count(),
                'accepted' => EkycRegistration::where('status', 'accepted')->count(),
                'rejected' => EkycRegistration::where('status', 'rejected')->count(),
            ];

            // Verification rate (accepted / total submitted)
            $total_submitted = EkycRegistration::whereIn('status', ['submitted', 'accepted', 'rejected'])->count();
            $total_accepted = EkycRegistration::where('status', 'accepted')->count();
            $verification_rate = $total_submitted > 0 ? round(($total_accepted / $total_submitted) * 100, 1) : 0;

            return view('dashboard', compact('stats', 'recent_ekyc', 'ekyc_by_status', 'verification_rate'));
        }

        // Untuk user biasa, tampilkan dashboard user
        $ekyc = EkycRegistration::where('user_id', auth()->id())->first();
        
        return view('dashboard', compact('ekyc'));
    }
}

