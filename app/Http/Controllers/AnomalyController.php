<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;

class AnomalyController extends Controller
{
    public function index()
    {
        // Contoh logika deteksi anomali: Skor yang sangat rendah ( < 2.0)
        // Atau skor yang jauh berbeda dari rata-rata (disederhanakan untuk demo)
        $totalEvaluations = Evaluation::count();
        $anomalies = Evaluation::with(['event', 'evaluator', 'evaluatee.user'])
            ->where('final_score', '<', 2.0)
            ->latest()
            ->paginate(10);

        $totalAnomalies = $anomalies->total();
        $avgDeviation = Evaluation::where('final_score', '<', 2.0)->avg('final_score') ?? 0;
        
        // Akurasi = Persentase data yang TIDAK anomali
        $accuracy = $totalEvaluations > 0 
            ? round((($totalEvaluations - $totalAnomalies) / $totalEvaluations) * 100) 
            : 100;

        return view('deteksi_anomali.index', compact('anomalies', 'totalAnomalies', 'avgDeviation', 'accuracy'));
    }
}
