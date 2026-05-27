<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\CommitteeMember;
use App\Models\Event;
use Illuminate\Http\Request;

class DashboardPanitiaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Dapatkan semua keanggotaan panitia (CommitteeMember) dari user ini
        $myCommitteeMemberships = CommitteeMember::where('user_id', $user->id)->get();

        if ($myCommitteeMemberships->isEmpty()) {
            return view('dashboard_panitia.index', [
                'avgScore' => 0,
                'totalEvaluationsPerformed' => 0,
                'totalTasks' => 0,
                'progress' => 0,
                'kriteriaScores' => [],
                'evaluationsToPerform' => \App\Models\CommitteeMember::whereNull('id')->paginate(5),
                'evaluationCriterias' => \App\Models\EvaluationCriteria::all(),
                'myEvents' => collect(),
                'selectedEventId' => null,
                'selectedEventName' => 'Belum ada Event'
            ]);
        }

        // Dapatkan semua ID event yang diikuti user ini
        $myEventIds = $myCommitteeMemberships->load('division')->pluck('division.event_id')->unique()->filter();
        $myEvents = Event::whereIn('id', $myEventIds)->get();

        $selectedEventId = $request->input('event_id') ?? ($myEvents->first()->id ?? null);
        $selectedEventName = $myEvents->where('id', $selectedEventId)->first()->name ?? 'Belum ada Event';

        // Filter memberships for the selected event only
        $myActiveMemberships = $myCommitteeMemberships->filter(function($cm) use ($selectedEventId) {
            return $cm->division && $cm->division->event_id == $selectedEventId;
        });

        // 1. Rata-rata Skor Saya (sebagai evaluatee) pada event terpilih
        $avgScore = Evaluation::whereIn('evaluatee_id', $myActiveMemberships->pluck('id'))->avg('final_score') ?? 0;

        // 2. Jumlah Panitia yang sudah saya nilai (sebagai evaluator) pada event ini
        $totalEvaluationsPerformed = Evaluation::where('evaluator_id', $user->id)
            ->whereHas('evaluatee.division', function($q) use ($selectedEventId) {
                $q->where('event_id', $selectedEventId);
            })
            ->whereNotNull('final_score')
            ->count();

        // 3. Progress Penilaian (Tugas saya sebagai evaluator)
        // Total tugas adalah jumlah panitia lain di event yang sama
        $totalTasks = CommitteeMember::whereHas('division', function ($query) use ($selectedEventId) {
            $query->where('event_id', $selectedEventId);
        })->where('user_id', '!=', $user->id)->count();
        
        $progress = $totalTasks > 0 ? round(($totalEvaluationsPerformed / $totalTasks) * 100) : 0;

        // 4. Skor per Kriteria (Real Data dari database)
        $kriteriaScores = [];
        $myEvaluationIds = Evaluation::whereIn('evaluatee_id', $myActiveMemberships->pluck('id'))->pluck('id');
        $allCriterias = \App\Models\EvaluationCriteria::all();
        
        foreach ($allCriterias as $crit) {
            $avgCrit = \App\Models\EvaluationDetail::whereIn('evaluation_id', $myEvaluationIds)
                ->where('criteria_id', $crit->id)
                ->avg('score') ?? 0;
            $kriteriaScores[$crit->name] = $avgCrit;
        }

        // 5. Daftar Panitia yang harus saya nilai (Tabel) pada event terpilih
        // Ambil semua panitia di event yang sama, kecuali diri sendiri
        $evaluationsToPerform = CommitteeMember::whereHas('division', function ($query) use ($selectedEventId) {
                $query->where('event_id', $selectedEventId);
            })
            ->where('user_id', '!=', $user->id)
            ->with(['user', 'division'])
            // Join dengan evaluation untuk cek status dinilai / belum
            ->with(['evaluationsAsEvaluatee' => function ($query) use ($user) {
                $query->where('evaluator_id', $user->id);
            }])
            ->latest()
            ->paginate(5);

        // Kriteria Evaluasi untuk Form Modal
        $evaluationCriterias = \App\Models\EvaluationCriteria::all();

        return view('dashboard_panitia.index', compact(
            'avgScore',
            'totalEvaluationsPerformed',
            'totalTasks',
            'progress',
            'kriteriaScores',
            'evaluationsToPerform',
            'evaluationCriterias',
            'myEvents',
            'selectedEventId',
            'selectedEventName'
        ));
    }
}
