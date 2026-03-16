<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/* HALAMAN REGISTER */
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

/* HALAMAN LOGIN (sementara dummy) */
Route::get('/login', function () {
    return "Halaman Login (Belum dibuat)";
})->name('login');