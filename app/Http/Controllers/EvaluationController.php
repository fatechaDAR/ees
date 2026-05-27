<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\EvaluationDetail;
use App\Models\CommitteeMember;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'evaluatee_id' => 'required|exists:committee_members,id',
            'scores' => 'required|array',
            'scores.*' => 'numeric|min:1|max:5',
            'feedback' => 'nullable|string'
        ]);

        $evaluator = auth()->user();
        $evaluatee = CommitteeMember::with('division')->findOrFail($request->evaluatee_id);

        // Validasi No Self
        if ($evaluator->id === $evaluatee->user_id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menilai diri sendiri.');
        }

        // Validasi No Double
        $existingEvaluation = Evaluation::where('evaluator_id', $evaluator->id)
                                        ->where('evaluatee_id', $evaluatee->id)
                                        ->first();
        if ($existingEvaluation) {
            return redirect()->back()->with('error', 'Anda sudah memberikan penilaian untuk rekan ini.');
        }

        // Hitung rata-rata nilai
        $totalScore = array_sum($request->scores);
        $count = count($request->scores);
        $finalScore = $count > 0 ? $totalScore / $count : 0;

        // Simpan ke tabel evaluations
        $evaluation = Evaluation::create([
            'event_id' => $evaluatee->division->event_id,
            'evaluator_id' => $evaluator->id,
            'evaluatee_id' => $evaluatee->id,
            'final_score' => $finalScore,
            'feedback' => $request->feedback
        ]);

        // Simpan rincian ke evaluation_details
        foreach ($request->scores as $criteriaId => $score) {
            EvaluationDetail::create([
                'evaluation_id' => $evaluation->id,
                'criteria_id' => $criteriaId,
                'score' => $score
            ]);
        }

        return redirect()->back()->with('success', 'Penilaian berhasil disimpan.');
    }
}
