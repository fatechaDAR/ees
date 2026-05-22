<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Event;
use Illuminate\Http\Request;

class AnomalyController extends Controller
{
    public function index(Request $request)
    {
        $query = Evaluation::query();

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Default to showing only anomalies on first load
        $isAnomalyFiltered = $request->has('only_anomaly') || count($request->all()) == 0;

        $totalEvaluationsQuery = clone $query;
        $totalEvaluations = $totalEvaluationsQuery->count();

        // Calculate total anomalies for the KPI based on filtered event
        $anomaliesQuery = clone $query;
        $totalAnomalies = $anomaliesQuery->where('final_score', '<', 2.0)->count();

        // Calculate average deviation for the KPI based on filtered event
        $avgDeviationQuery = clone $query;
        $avgDeviation = $avgDeviationQuery->where('final_score', '<', 2.0)->avg('final_score') ?? 0;
        
        // Akurasi = Persentase data yang TIDAK anomali
        $accuracy = $totalEvaluations > 0 
            ? round((($totalEvaluations - $totalAnomalies) / $totalEvaluations) * 100) 
            : 100;

        if ($isAnomalyFiltered) {
            $query->where('final_score', '<', 2.0);
        }

        $anomalies = $query->with(['event', 'evaluator', 'evaluatee.user'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $events = Event::all();

        return view('deteksi_anomali.index', compact('anomalies', 'totalAnomalies', 'avgDeviation', 'accuracy', 'events', 'isAnomalyFiltered'));
    }
}
