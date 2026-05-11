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

/* DASHBOARD UTAMA (Saat login suskes) */
Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware('auth')->name('dashboard');

/* DASHBOARD ADMIN */
Route::get('/dashboard-admin', function () {
    return view('dashboard_admin.index');
})->middleware('auth')->name('dashboard.admin');

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

/* DASHBOARD PANITIA */
Route::get('/dashboard-panitia', function () {
    return view('dashboard_panitia.index');
})->middleware('auth')->name('dashboard.panitia');

/* Hasil Evaluasi */
Route::get('/hasil-evaluasi-panitia', function () {
    return view('hasil_evaluasi_panitia.index');
})->name('hasil-panitia.index');