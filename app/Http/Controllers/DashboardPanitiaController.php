<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\CommitteeMember;
use App\Models\Event;
use Illuminate\Http\Request;

class DashboardPanitiaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $committeeMember = CommitteeMember::where('user_id', $user->id)->first();

        if (!$committeeMember) {
            return view('dashboard_panitia.index', [
                'avgScore' => 0,
                'totalEvaluations' => 0,
                'pendingEvaluations' => 0,
                'progress' => 0,
                'kriteriaScores' => [],
                'evaluationsToPerform' => collect()
            ]);
        }

        // 1. Rata-rata Skor Saya
        $avgScore = Evaluation::where('evaluatee_id', $committeeMember->id)->avg('final_score') ?? 0;

        // 2. Jumlah Panitia yang sudah saya nilai
        $totalEvaluationsPerformed = Evaluation::where('evaluator_id', $committeeMember->id)
            ->whereNotNull('final_score')
            ->count();

        // 3. Progress Penilaian (Tugas saya sebagai evaluator)
        $totalTasks = Evaluation::where('evaluator_id', $committeeMember->id)->count();
        $progress = $totalTasks > 0 ? round(($totalEvaluationsPerformed / $totalTasks) * 100) : 0;

        // 4. Skor per Kriteria (Disederhanakan, mengambil rata-rata kriteria dari evaluasi saya)
        // Catatan: Di database asli mungkin ada tabel evaluation_details, di sini kita simulasi
        $kriteriaScores = [
            'KERJA SAMA' => Evaluation::where('evaluatee_id', $committeeMember->id)->avg('teamwork') ?? 0,
            'DISIPLIN' => Evaluation::where('evaluatee_id', $committeeMember->id)->avg('discipline') ?? 0,
            'TANGGUNG JAWAB' => Evaluation::where('evaluatee_id', $committeeMember->id)->avg('responsibility') ?? 0,
        ];

        // 5. Daftar Panitia yang harus saya nilai (Tabel)
        $evaluationsToPerform = Evaluation::where('evaluator_id', $committeeMember->id)
            ->with(['evaluatee.user', 'evaluatee.division'])
            ->latest()
            ->paginate(5);

        return view('dashboard_panitia.index', compact(
            'avgScore',
            'totalEvaluationsPerformed',
            'totalTasks',
            'progress',
            'kriteriaScores',
            'evaluationsToPerform'
        ));
    }
}
