<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil total panitia (user dengan role 'panitia')
        $totalPanitia = User::where('role', 'panitia')->count();

        // Mengambil daftar data panitia dari tabel users
        $dataPanitia = User::where('role', 'panitia')
            ->latest()
            ->get();

        // Mengambil total evaluasi
        $totalEvaluasi = Evaluation::count();

        // Mengambil data evaluasi terbaru beserta relasi (eager loading)
        $evaluasi = Evaluation::with(['event', 'evaluator', 'evaluatee'])
            ->latest()
            ->get();

        // Kirim data ke view
        return view('dashboard.index', compact(
            'totalPanitia',
            'dataPanitia',
            'totalEvaluasi',
            'evaluasi'
        ));
    }
}
