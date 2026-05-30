<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Division;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function index()
    {
        // Ranking Panitia (Global)
        $rankings = Evaluation::with(['evaluatee.user', 'evaluatee.division'])
            ->whereNotNull('final_score')
            ->select('evaluatee_id')
            ->selectRaw('AVG(final_score) as avg_score')
            ->groupBy('evaluatee_id')
            ->orderByDesc('avg_score')
            ->paginate(10);

        // Top 3 untuk Podium
        $top3 = Evaluation::with(['evaluatee.user', 'evaluatee.division'])
            ->whereNotNull('final_score')
            ->select('evaluatee_id')
            ->selectRaw('AVG(final_score) as avg_score')
            ->groupBy('evaluatee_id')
            ->orderByDesc('avg_score')
            ->limit(3)
            ->get();

        // Ranking Divisi (untuk insight)
        $divisionRankings = Evaluation::join('committee_members', 'evaluations.evaluatee_id', '=', 'committee_members.id')
            ->join('divisions', 'committee_members.division_id', '=', 'divisions.id')
            ->whereNotNull('evaluations.final_score')
            ->select('divisions.name')
            ->selectRaw('AVG(evaluations.final_score) as avg_score')
            ->groupBy('divisions.id', 'divisions.name')
            ->orderByDesc('avg_score')
            ->get();

        $topDivision = $divisionRankings->first();
        
        // Kalkulasi Growth (Membandingkan rata-rata event terbaru vs sebelumnya)
        $eventIds = Evaluation::distinct()->pluck('event_id')->sortDesc()->values();
        $avgGrowth = "0%";
        if ($eventIds->count() >= 2) {
            $currentAvg = Evaluation::where('event_id', $eventIds[0])->whereNotNull('final_score')->avg('final_score') ?? 0;
            $previousAvg = Evaluation::where('event_id', $eventIds[1])->whereNotNull('final_score')->avg('final_score') ?? 0;
            
            if ($previousAvg > 0) {
                $growth = (($currentAvg - $previousAvg) / $previousAvg) * 100;
                $avgGrowth = ($growth >= 0 ? '+' : '') . number_format($growth, 1) . '%';
            }
        } elseif ($eventIds->count() == 1) {
            $avgGrowth = "New Event";
        }

        return view('ranking.index', compact('rankings', 'top3', 'divisionRankings', 'topDivision', 'avgGrowth'));
    }

    public function exportCsv()
    {
        $rankings = Evaluation::with(['evaluatee.user', 'evaluatee.division'])
            ->whereNotNull('final_score')
            ->select('evaluatee_id')
            ->selectRaw('AVG(final_score) as avg_score')
            ->groupBy('evaluatee_id')
            ->orderByDesc('avg_score')
            ->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=ranking_panitia.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Rank', 'Nama Panitia', 'Divisi', 'Nilai Akhir (Skor)'];

        $callback = function() use ($rankings, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $rank = 1;
            foreach ($rankings as $row) {
                fputcsv($file, [
                    $rank++,
                    $row->evaluatee->user->name ?? 'N/A',
                    $row->evaluatee->division->name ?? 'N/A',
                    number_format($row->avg_score, 2)
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
