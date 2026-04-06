<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/login');
});

/* HALAMAN REGISTER */
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [AuthController::class, 'store']); // Menerima data pendaftaran

/* HALAMAN LOGIN */
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'authenticate']); // Menerima data login

/* LOGOUT */
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/* DASHBOARD (Sementara) */
Route::get('/dashboard', function () {
    return "Selamat datang di Dashboard! Anda berhasil login sebagai: " . auth()->user()->role;
})->middleware('auth')->name('dashboard');