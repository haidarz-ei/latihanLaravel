<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\MatkulController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/helo', function () {
    return "HELLO WORLD dari laravel";
});

Route::get('/nama', function () {
    return "Madun 2025";
});


Route::get('/mahasiswa', [MahasiswaController::class, 'index']);
Route::post('/mahasiswa', [MahasiswaController::class, 'store']);

Route::get('/ruangan', [RuanganController::class, 'index']);
Route::post('/ruangan', [RuanganController::class, 'store']);

Route::get('/matkul', [MatkulController::class, 'index']);
Route::post('/matkul', [MatkulController::class, 'store']);


