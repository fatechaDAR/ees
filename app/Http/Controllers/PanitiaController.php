<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CommitteeMember;
use Illuminate\Http\Request;

class PanitiaController extends Controller
{
    public function pilihEvent(Request $request)
    {
        $registeredUserId = session('registered_user_id');
        if (!$registeredUserId) {
            return redirect('/login');
        }

        $events = \App\Models\Event::with('divisions')->orderBy('created_at', 'desc')->get();
        return view('pilih_event.index', compact('events'));
    }

    public function storePilihEvent(Request $request)
    {
        $registeredUserId = session('registered_user_id');
        if (!$registeredUserId) {
            return redirect('/login');
        }

        $request->validate([
            'event_id' => 'required|exists:events,id',
            'division_id' => 'required|exists:divisions,id',
        ]);

        \App\Models\CommitteeMember::create([
            'user_id' => $registeredUserId,
            'division_id' => $request->division_id,
            'position' => 'anggota',
        ]);

        $request->session()->forget('registered_user_id');

        return redirect('/login')->with('success', 'Berhasil memilih divisi. Silakan login.');
    }

    public function index(Request $request)
    {
        // Mengambil semua event untuk dropdown
        $events = \App\Models\Event::orderBy('created_at', 'desc')->get();

        $selectedEventId = $request->input('event_id');
        if (!$selectedEventId && $events->isNotEmpty()) {
            $selectedEventId = $events->first()->id;
        }

        // Mengambil semua divisi untuk event ini (untuk filter divisi)
        $divisions = \App\Models\Division::where('event_id', $selectedEventId)->orderBy('name')->get();

        // Mengambil total panitia dari tabel users yang terdaftar di event terpilih
        $totalPanitia = User::where('role', 'panitia')
            ->whereHas('committeeMembers.division', function($q) use ($selectedEventId) {
                $q->where('event_id', $selectedEventId);
            })->count();

        // Total Divisi Aktif pada event terpilih
        $totalDivisi = \App\Models\Division::where('event_id', $selectedEventId)->count();

        // Evaluasi Tertunda pada event terpilih
        $pendingEvaluations = \App\Models\Evaluation::whereNull('final_score')
            ->whereHas('evaluatee.division', function($q) use ($selectedEventId) {
                $q->where('event_id', $selectedEventId);
            })->count();

        // Rerata Performa pada event terpilih
        $avgPerformance = \App\Models\Evaluation::whereHas('evaluatee.division', function($q) use ($selectedEventId) {
                $q->where('event_id', $selectedEventId);
            })->avg('final_score') ?? 0;

        // Mengambil daftar panitia (users dengan role panitia) yang terdaftar di event terpilih
        $query = User::where('role', 'panitia');
        
        if ($selectedEventId) {
            $query->whereHas('committeeMembers.division', function($q) use ($selectedEventId) {
                $q->where('event_id', $selectedEventId);
            });
        }

        // Filter pencarian nama
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter divisi
        if ($request->filled('division_id')) {
            $query->whereHas('committeeMembers', function($q) use ($request) {
                $q->where('division_id', $request->division_id);
            });
        }

        // Eager load committeeMembers & division
        if ($selectedEventId) {
            $query->with(['committeeMembers' => function($q) use ($selectedEventId) {
                $q->whereHas('division', function($divQ) use ($selectedEventId) {
                    $divQ->where('event_id', $selectedEventId);
                })->with('division');
            }]);
        } else {
            $query->with('committeeMembers.division');
        }

        $panitiaList = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('manajemen_panitia.index', compact(
            'totalPanitia', 
            'totalDivisi', 
            'pendingEvaluations', 
            'avgPerformance', 
            'panitiaList',
            'events',
            'selectedEventId',
            'divisions'
        ));
    }

    public function export(Request $request)
    {
        $selectedEventId = $request->input('event_id');

        $query = User::where('role', 'panitia');

        if ($selectedEventId) {
            $query->whereHas('committeeMembers.division', function($q) use ($selectedEventId) {
                $q->where('event_id', $selectedEventId);
            });
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('division_id')) {
            $query->whereHas('committeeMembers', function($q) use ($request) {
                $q->where('division_id', $request->division_id);
            });
        }

        if ($selectedEventId) {
            $query->with(['committeeMembers' => function($q) use ($selectedEventId) {
                $q->whereHas('division', function($divQ) use ($selectedEventId) {
                    $divQ->where('event_id', $selectedEventId);
                })->with('division');
            }]);
        } else {
            $query->with('committeeMembers.division');
        }

        $list = $query->get();

        $event = \App\Models\Event::find($selectedEventId);
        $eventName = $event ? preg_replace('/[^a-zA-Z0-9_]/', '_', $event->name) : 'event';
        $filename = 'data_panitia_' . $eventName . '_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($list) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Nama', 'Email', 'Divisi', 'Jabatan']);

            foreach ($list as $i => $u) {
                $cm = $u->committeeMembers->first();
                fputcsv($file, [
                    $i + 1,
                    $u->name ?? '-',
                    $u->email ?? '-',
                    $cm->division->name ?? '-',
                    ucfirst($cm->position ?? 'member'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
                'evaluations' => collect(),
                'eventName' => 'Belum ada Event'
            ]);
        }

        $eventName = $committeeMember->division && $committeeMember->division->event ? $committeeMember->division->event->name : 'Event';

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

        return view('hasil_evaluasi_panitia.index', compact('avgScore', 'rank', 'totalPanitia', 'evaluations', 'eventName'));
    }

    public function exportPdf()
    {
        $user = auth()->user();
        $committeeMember = \App\Models\CommitteeMember::where('user_id', $user->id)->first();

        if (!$committeeMember) {
            $avgScore = 0;
            $rank = '-';
            $totalPanitia = 0;
            $evaluations = collect();
            $eventName = 'Belum ada Event';
        } else {
            $eventName = $committeeMember->division && $committeeMember->division->event ? $committeeMember->division->event->name : 'Event';

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
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('hasil_evaluasi_panitia.pdf', compact('avgScore', 'rank', 'totalPanitia', 'evaluations', 'eventName', 'user'));
        return $pdf->download('Laporan_Evaluasi_' . str_replace(' ', '_', $user->name) . '.pdf');
    }
}
