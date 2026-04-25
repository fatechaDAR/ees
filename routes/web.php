<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/register');

/* HALAMAN REGISTER */
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

/*Login*/
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

/*Dashboard*/
Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('dashboard');

/*Manajemen Event*/
Route::get('/manajemen-event', function () {
    return view('manajemen_event.index'); // Sesuaikan dengan nama folder baru
})->name('event.index');

/*Manajemen Divisi*/
Route::get('/manajemen-divisi', function () {
    return view('manajemen_divisi.index');
})->name('divisi.index');

/*Manajemen Panitia*/
Route::get('/manajemen-panitia', function () {
    return view('manajemen_panitia.index');
})->name('panitia.index');

/*Manajemen User*/
Route::get('/manajemen-user', function () {
    return view('manajemen_user.index');
})->name('user.index');

/*Monitoring Evaluasi*/
Route::get('/monitoring-evaluasi', function () {
    return view('monitoring_evaluasi.index');
})->name('monitoring.index');

/*Hasil Evaluasi*/
Route::get('/hasil-evaluasi', function () {
    return view('hasil_evaluasi.index');
})->name('hasil.index');

/*Deteksi Anomali*/
Route::get('/deteksi-anomali', function () {
    return view('deteksi_anomali.index');
})->name('anomali.index');

/*Ranking*/
Route::get('/ranking', function () {
    return view('ranking.index');
})->name('ranking.index');