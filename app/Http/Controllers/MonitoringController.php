<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Division;
use App\Models\Event;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $adminId = auth()->id();
        $query = Evaluation::whereHas('event', function ($q) use ($adminId) {
            $q->where('admin_id', $adminId);
        });

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('division_id')) {
            $query->whereHas('evaluatee', function ($q) use ($request) {
                $q->where('division_id', $request->division_id);
            });
        }

        // Data KPI
        $totalEvaluations = $query->count();
        $avgScoreQuery = clone $query;
        $avgScore = $avgScoreQuery->avg('final_score') ?? 0;
        
        $pendingQuery = clone $query;
        $pendingEvaluations = $pendingQuery->whereNull('final_score')->count();

        // Data untuk Filter
        $events = Event::where('admin_id', $adminId)->get();
        $divisions = Division::whereHas('event', function ($q) use ($adminId) {
            $q->where('admin_id', $adminId);
        })->get();

        // Daftar Evaluasi
        $evaluations = $query->with(['event', 'evaluator', 'evaluatee.user', 'evaluatee.division'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

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
