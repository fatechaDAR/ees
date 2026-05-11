<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CommitteeMember;
use Illuminate\Http\Request;

class PanitiaController extends Controller
{
    public function index()
    {
        // Mengambil total panitia dari tabel users
        $totalPanitia = User::where('role', 'panitia')->count();

        // Total Divisi Aktif
        $totalDivisi = \App\Models\Division::count();

        // Evaluasi Tertunda (Skor belum diisi/null)
        $pendingEvaluations = \App\Models\Evaluation::whereNull('final_score')->count();

        // Rerata Performa Global
        $avgPerformance = \App\Models\Evaluation::avg('final_score') ?? 0;

        // Mengambil daftar panitia (users dengan role panitia)
        $panitiaList = User::where('role', 'panitia')
            ->with('committeeMembers.division')
            ->latest()
            ->paginate(10);

        return view('manajemen_panitia.index', compact(
            'totalPanitia', 
            'totalDivisi', 
            'pendingEvaluations', 
            'avgPerformance', 
            'panitiaList'
        ));
    }

    public function personalEvaluation()
    {
        $user = auth()->user();
        $committeeMember = \App\Models\CommitteeMember::where('user_id', $user->id)->first();

        if (!$committeeMember) {
            return view('hasil_evaluasi_panitia.index', [
                'avgScore' => 0,
                'rank' => '-',
                'totalPanitia' => 0,
                'evaluations' => collect()
            ]);
        }

        $avgScore = \App\Models\Evaluation::where('evaluatee_id', $committeeMember->id)->avg('final_score') ?? 0;
        $allRankings = \App\Models\Evaluation::selectRaw('evaluatee_id, AVG(final_score) as avg_score')
            ->groupBy('evaluatee_id')
            ->orderByDesc('avg_score')
            ->get();
        
        $rankIndex = $allRankings->search(fn($item) => $item->evaluatee_id == $committeeMember->id);
        $rank = $rankIndex !== false ? $rankIndex + 1 : '-';
        $totalPanitia = $allRankings->count();

        $evaluations = \App\Models\Evaluation::where('evaluatee_id', $committeeMember->id)
            ->with('evaluator')
            ->latest()
            ->get();

        return view('hasil_evaluasi_panitia.index', compact('avgScore', 'rank', 'totalPanitia', 'evaluations'));
    }
}
