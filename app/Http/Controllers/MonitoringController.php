<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Division;
use App\Models\Event;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        // Data KPI
        $totalEvaluations = Evaluation::count();
        $avgScore = Evaluation::avg('final_score') ?? 0;
        $pendingEvaluations = Evaluation::whereNull('final_score')->count();

        // Data untuk Filter
        $events = Event::all();
        $divisions = Division::all();

        // Daftar Evaluasi
        $evaluations = Evaluation::with(['event', 'evaluator', 'evaluatee.user', 'evaluatee.division'])
            ->latest()
            ->paginate(10);

        return view('monitoring_evaluasi.index', compact(
            'totalEvaluations',
            'avgScore',
            'pendingEvaluations',
            'events',
            'divisions',
            'evaluations'
        ));
    }
}
