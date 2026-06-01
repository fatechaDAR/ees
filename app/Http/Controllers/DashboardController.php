<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $adminId = auth()->id();

        // Mengambil total panitia (user dengan role 'panitia')
        $totalPanitia = User::where('role', 'panitia')
            ->whereHas('committeeMembers.division.event', function ($q) use ($adminId) {
                $q->where('admin_id', $adminId);
            })->count();

        // Mengambil daftar data panitia dari tabel users
        $dataPanitia = User::where('role', 'panitia')
            ->whereHas('committeeMembers.division.event', function ($q) use ($adminId) {
                $q->where('admin_id', $adminId);
            })->latest()->get();

        // Mengambil total evaluasi
        $totalEvaluasi = Evaluation::whereHas('event', function ($q) use ($adminId) {
            $q->where('admin_id', $adminId);
        })->count();

        // Mengambil data evaluasi terbaru beserta relasi (eager loading)
        $evaluasi = Evaluation::with(['event', 'evaluator', 'evaluatee'])
            ->whereHas('event', function ($q) use ($adminId) {
                $q->where('admin_id', $adminId);
            })->latest()->get();

        // Kirim data ke view
        return view('dashboard.index', compact(
            'totalPanitia',
            'dataPanitia',
            'totalEvaluasi',
            'evaluasi'
        ));
    }
}
