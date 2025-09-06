<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/helo', function () {
    return "HELLO WORLD dari laravel";
});

Route::get('/nama', function () {
    return "Madun 2025";
});