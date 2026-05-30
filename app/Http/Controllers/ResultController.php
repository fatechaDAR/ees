<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $query = Evaluation::whereNotNull('final_score');

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Data KPI
        $avgScoreQuery = clone $query;
        $avgScoreGlobal = $avgScoreQuery->avg('final_score') ?? 0;
        
        $totalPanitia = User::where('role', 'panitia')->count();
        
        $sangatBaikQuery = clone $query;
        $totalSangatBaik = $sangatBaikQuery->where('final_score', '>=', 4.0)->count();
        
        $totalEvaluasi = $query->count();
        $persentaseSangatBaik = $totalEvaluasi > 0 ? round(($totalSangatBaik / $totalEvaluasi) * 100) : 0;

        // Data untuk Filter
        $events = Event::all();

        // Daftar Hasil Evaluasi
        $results = $query->with(['event', 'evaluator', 'evaluatee.user', 'evaluatee.division'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('hasil_evaluasi.index', compact(
            'avgScoreGlobal', 
            'totalPanitia', 
            'persentaseSangatBaik',
            'events', 
            'results'
        ));
    }

    public function exportCsv(Request $request)
    {
        $query = Evaluation::whereNotNull('final_score');

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $results = $query->with(['event', 'evaluator', 'evaluatee.user', 'evaluatee.division'])
            ->latest()
            ->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=hasil_evaluasi.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID Panitia', 'Nama Panitia', 'Divisi', 'Event', 'Nilai Rata-rata', 'Kategori', 'Evaluator'];

        $callback = function() use ($results, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($results as $res) {
                $score = $res->final_score;
                $cat = 'Sangat Kurang';
                if($score >= 4.5) { $cat = 'Sangat Baik'; }
                elseif($score >= 3.5) { $cat = 'Baik'; }
                elseif($score >= 2.5) { $cat = 'Cukup'; }
                elseif($score >= 1.5) { $cat = 'Kurang'; }

                $row = [
                    'PNT-' . str_pad($res->evaluatee->id ?? 0, 3, '0', STR_PAD_LEFT),
                    $res->evaluatee->user->name ?? 'N/A',
                    $res->evaluatee->division->name ?? 'N/A',
                    $res->event->name ?? 'N/A',
                    number_format($score, 2),
                    $cat,
                    $res->evaluator->name ?? 'N/A'
                ];

                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
