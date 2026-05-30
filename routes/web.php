<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PanitiaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\AnomalyController;
use App\Http\Controllers\RankingController;

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
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

/* DASHBOARD ADMIN */
Route::get('/dashboard-admin', [AdminController::class, 'index'])->middleware('auth')->name('dashboard.admin');
Route::post('/profile/update-foto', [\App\Http\Controllers\UserController::class, 'updatePhoto'])->middleware('auth')->name('profile.update-foto');

/*Manajemen Event*/
Route::get('/manajemen-event', [EventController::class, 'index'])->name('event.index');
Route::get('/manajemen-event/export', [EventController::class, 'export'])->name('event.export');
Route::post('/manajemen-event', [EventController::class, 'store'])->name('event.store');
Route::put('/manajemen-event/{event}', [EventController::class, 'update'])->name('event.update');
Route::delete('/manajemen-event/{event}', [EventController::class, 'destroy'])->name('event.destroy');

/*Manajemen Divisi*/
Route::get('/manajemen-divisi', [DivisionController::class, 'index'])->name('divisi.index');
Route::get('/manajemen-divisi/export', [DivisionController::class, 'export'])->name('divisi.export');
Route::post('/manajemen-divisi', [DivisionController::class, 'store'])->name('divisi.store');
Route::put('/manajemen-divisi/{division}', [DivisionController::class, 'update'])->name('divisi.update');
Route::delete('/manajemen-divisi/{division}', [DivisionController::class, 'destroy'])->name('divisi.destroy');

/*Manajemen Panitia*/
Route::get('/manajemen-panitia', [PanitiaController::class, 'index'])->name('panitia.index');
Route::get('/manajemen-panitia/export', [PanitiaController::class, 'export'])->name('panitia.export');

/*Manajemen User*/
Route::get('/manajemen-user', [UserController::class, 'index'])->name('user.index');

/*Monitoring Evaluasi*/
Route::get('/monitoring-evaluasi', [MonitoringController::class, 'index'])->name('monitoring.index');

/*Hasil Evaluasi*/
Route::get('/hasil-evaluasi', [ResultController::class, 'index'])->name('hasil.index');
Route::get('/hasil-evaluasi/export', [ResultController::class, 'exportCsv'])->name('hasil.export');

/*Deteksi Anomali*/
Route::get('/deteksi-anomali', [AnomalyController::class, 'index'])->name('anomali.index');

/*Ranking*/
Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');
Route::get('/ranking/export', [RankingController::class, 'exportCsv'])->name('ranking.export');

/* DASHBOARD PANITIA */
Route::get('/dashboard-panitia', [\App\Http\Controllers\DashboardPanitiaController::class, 'index'])->middleware('auth')->name('dashboard.panitia');

/* Hasil Evaluasi Panitia */
Route::get('/hasil-evaluasi-panitia', [PanitiaController::class, 'personalEvaluation'])->middleware('auth')->name('hasil-panitia.index');
Route::get('/hasil-evaluasi-panitia/pdf', [PanitiaController::class, 'exportPdf'])->middleware('auth')->name('hasil-panitia.pdf');

/* Proses Evaluasi Panitia */
Route::post('/evaluasi/store', [\App\Http\Controllers\EvaluationController::class, 'store'])->middleware('auth')->name('evaluasi.store');

//cek desain UI Panitia
Route::get('/cek-desain-panitia', function () {
    
    // 1. array data orang
    $dataOrang = [
        (object) [
            'nama' => 'Arya Mahendra',
            'divisi' => 'Acara',
            'status' => 'Sudah Dinilai',
            'final_score' => 4.8
        ],
        (object) [
            'nama' => 'Siska Putri',
            'divisi' => 'Humas',
            'status' => 'Belum Dinilai',
            'final_score' => null
        ],
        (object) [
            'nama' => 'Raka Kusuma',
            'divisi' => 'Logistik',
            'status' => 'Sudah Dinilai',
            'final_score' => 4.2
        ]
    ];

    // 2. array jadi Paginator Palsu
    // Angka 15 = total data, Angka 5 = data per halaman, Angka 1 = halaman saat ini
    $evaluationsToPerform = new \Illuminate\Pagination\LengthAwarePaginator($dataOrang, 15, 5, 1);

    return view('dashboard_panitia.index', [
        'avgScore' => 4.5,
        'totalEvaluationsPerformed' => 12,
        'totalTasks' => 15,
        'progress' => 80,
        
        'kriteriaScores' => [
            'KERJA SAMA' => 4.2,
            'DISIPLIN' => 4.8,
            'TANGGUNG JAWAB' => 4.5,
        ],
        
        // 3. 
        'evaluationsToPerform' => $evaluationsToPerform
    ]);
});

/* Pilih Event (Setelah Register tapi sebelum Login) */
Route::get('/pilih-event', [PanitiaController::class, 'pilihEvent'])->name('pilih-event.index');
Route::post('/pilih-event', [PanitiaController::class, 'storePilihEvent'])->name('pilih-event.store');