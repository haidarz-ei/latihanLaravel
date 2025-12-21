<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\MatkulController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\Auth\StudentRegisterController;
use App\Http\Controllers\EkycController;
use App\Http\Controllers\Admin\EkycAdminController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\Admin\LandingSettingController;
use App\Http\Controllers\Admin\LandingProgramController;
use App\Http\Controllers\Admin\LandingNavController;
use App\Http\Controllers\Admin\LandingFooterController;
use App\Http\Controllers\Admin\LandingAboutController;

use Illuminate\Support\Facades\Route;

// Halaman Landing
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/helo', function () {
    return "HELLO WORLD dari laravel";
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mahasiswae
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::get('/mahasiswa/{id}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
    Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');

    // Ruangan, Matkul, Dosen (resource)
    Route::resource('ruangan', RuanganController::class)->middleware('auth');
    Route::resource('matkul', MatkulController::class)->middleware('auth');
    Route::resource('dosen', DosenController::class)->middleware('auth');

    // Admin eKYC
    Route::prefix('admin')->middleware('auth')->group(function () {
        Route::get('/ekyc', [EkycAdminController::class, 'index'])->name('admin.ekyc.index');
        Route::get('/ekyc/{id}', [EkycAdminController::class, 'show'])->name('admin.ekyc.show');
        Route::post('/ekyc/{id}/verify', [EkycAdminController::class, 'verify'])->name('admin.ekyc.verify');
    });

    /** Landing Page CMS */
    Route::prefix('admin/landing')->name('admin.landing.')->group(function () {
        Route::resource('settings', LandingSettingController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);
        Route::resource('programs', LandingProgramController::class)->except(['show', 'create', 'edit']);
        Route::resource('nav', LandingNavController::class)->except(['show', 'create', 'edit']);
        Route::resource('footer', LandingFooterController::class)->except(['show', 'create', 'edit']);
        Route::post('footer/reorder', [LandingFooterController::class, 'reorder'])->name('footer.reorder');
        Route::patch('footer/{id}/status', [LandingFooterController::class, 'toggleStatus'])->name('footer.toggleStatus');
        Route::post('footer/brand', [LandingFooterController::class, 'saveBrand'])->name('footer.brand.save');
        Route::resource('about', LandingAboutController::class)->except(['show', 'create', 'store']);
        Route::post('about/description', [LandingAboutController::class, 'storeDescription'])->name('about.description.store');
        Route::put('about/description/{id}', [LandingAboutController::class, 'updateDescription'])->name('about.description.update');
        Route::delete('about/description/{id}', [LandingAboutController::class, 'destroyDescription'])->name('about.description.destroy');
    });
});

// Register Mahasiswa
Route::get('/register-mahasiswa', [StudentRegisterController::class, 'showRegistrationForm'])->name('register.mahasiswa');
Route::post('/register-mahasiswa', [StudentRegisterController::class, 'register']);

// eKYC
Route::middleware('auth')->prefix('ekyc')->group(function () {
    Route::get('status', [EkycController::class, 'showStatus'])->name('ekyc.status');

    Route::get('step1', [EkycController::class, 'step1'])->name('ekyc.step1');
    Route::post('step1', [EkycController::class, 'storeStep1'])->name('ekyc.storeStep1');

    Route::get('step2', [EkycController::class, 'step2'])->name('ekyc.step2');
    Route::post('step2', [EkycController::class, 'storeStep2'])->name('ekyc.step2.store');

    Route::get('step3', [EkycController::class, 'showStep3'])->name('ekyc.step3');
    Route::post('step3', [EkycController::class, 'storeStep3'])->name('ekyc.step3.store');

    Route::get('step4', [EkycController::class, 'showStep4'])->name('ekyc.step4');
    Route::post('step4', [EkycController::class, 'storeStep4'])->name('ekyc.step4.store');

    Route::get('step5', [EkycController::class, 'step5'])->name('ekyc.step5');
});

require __DIR__.'/auth.php';
