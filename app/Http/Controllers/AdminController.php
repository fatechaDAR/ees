<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Evaluation;
use App\Models\Event;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $adminId = auth()->id();
        $evaluationQuery = Evaluation::whereHas('event', function ($q) use ($adminId) {
            $q->where('admin_id', $adminId);
        });

        // Data untuk KPI Cards
        $totalEvaluasi = (clone $evaluationQuery)->count();
        
        // Rata-rata Skor Keseluruhan
        $avgScore = (clone $evaluationQuery)->avg('final_score') ?? 0;
        
        // Progress penilaian
        $completedEvaluations = (clone $evaluationQuery)->whereNotNull('final_score')->count();
        $progress = $totalEvaluasi > 0 ? round(($completedEvaluations / $totalEvaluasi) * 100) : 0;

        // Data untuk Ranking (Top 3)
        $topPanitia = (clone $evaluationQuery)->with(['evaluatee.user', 'evaluatee.division'])
            ->select('evaluatee_id')
            ->selectRaw('AVG(final_score) as avg_score')
            ->groupBy('evaluatee_id')
            ->orderByDesc('avg_score')
            ->take(3)
            ->get();

        // Data untuk Bar Chart: Rata-rata per Event (Top 5 Event Terbaru)
        $eventScores = (clone $evaluationQuery)->with('event')
            ->select('event_id')
            ->selectRaw('AVG(final_score) as avg_score')
            ->groupBy('event_id')
            ->orderByDesc('event_id')
            ->take(5)
            ->get();

        // Data untuk Donut Chart: Distribusi Kategori Nilai
        $sangatBaik = (clone $evaluationQuery)->where('final_score', '>=', 4.0)->count();
        $baik = (clone $evaluationQuery)->where('final_score', '>=', 3.0)->where('final_score', '<', 4.0)->count();
        $cukup = (clone $evaluationQuery)->where('final_score', '>=', 2.0)->where('final_score', '<', 3.0)->count();
        $kurang = (clone $evaluationQuery)->where('final_score', '<', 2.0)->whereNotNull('final_score')->count();

        // Menghitung persentase
        $distribusi = [
            'sangat_baik' => $totalEvaluasi > 0 ? round(($sangatBaik / $totalEvaluasi) * 100) : 0,
            'baik' => $totalEvaluasi > 0 ? round(($baik / $totalEvaluasi) * 100) : 0,
            'cukup' => $totalEvaluasi > 0 ? round(($cukup / $totalEvaluasi) * 100) : 0,
            'kurang' => $totalEvaluasi > 0 ? round(($kurang / $totalEvaluasi) * 100) : 0,
        ];

        return view('dashboard_admin.index', compact(
            'totalEvaluasi', 
            'avgScore', 
            'progress',
            'topPanitia',
            'eventScores',
            'distribusi'
        ));
    }
}
