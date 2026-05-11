<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        // Data KPI
        $avgScoreGlobal = Evaluation::avg('final_score') ?? 0;
        $totalPanitia = User::where('role', 'panitia')->count();
        $totalSangatBaik = Evaluation::where('final_score', '>=', 4.0)->count();
        $totalEvaluasi = Evaluation::count();
        $persentaseSangatBaik = $totalEvaluasi > 0 ? round(($totalSangatBaik / $totalEvaluasi) * 100) : 0;

        // Data untuk Filter
        $events = Event::all();

        // Daftar Hasil Evaluasi
        $results = Evaluation::with(['event', 'evaluator', 'evaluatee.user', 'evaluatee.division'])
            ->whereNotNull('final_score')
            ->latest()
            ->paginate(10);

        return view('hasil_evaluasi.index', compact(
            'avgScoreGlobal', 
            'totalPanitia', 
            'persentaseSangatBaik',
            'events', 
            'results'
        ));
    }
}
