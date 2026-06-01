<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\CommitteeMember;
use App\Models\User;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        $events = \App\Models\Event::where('admin_id', auth()->id())->orderBy('created_at', 'desc')->get();

        $selectedEventId = $request->input('event_id');
        if (!$selectedEventId && $events->isNotEmpty()) {
            $selectedEventId = $events->first()->id;
        }

        // KPI: total divisi untuk event terpilih
        $totalDivisi = Division::where('event_id', $selectedEventId)->count();

        // KPI: total panitia pada event terpilih
        $totalPanitia = CommitteeMember::whereHas('division', function ($q) use ($selectedEventId) {
            $q->where('event_id', $selectedEventId);
        })->count();

        // Rerata panitia per divisi
        $avgPerDivisi = $totalDivisi > 0 ? round($totalPanitia / $totalDivisi, 1) : 0;

        // Query daftar divisi dengan filter pencarian
        $query = Division::withCount('committeeMembers')
            ->where('event_id', $selectedEventId)
            ->latest();

        if ($request->filled('search_divisi')) {
            $query->where('name', 'like', '%' . $request->search_divisi . '%');
        }

        $divisiList = $query->paginate(10)->withQueryString();

        return view('manajemen_divisi.index', compact(
            'totalDivisi',
            'totalPanitia',
            'avgPerDivisi',
            'divisiList',
            'events',
            'selectedEventId'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|min:3|max:255',
            'description' => 'nullable|string',
            'event_id'    => 'required|exists:events,id',
        ]);

        Division::create($validated);

        return redirect()->route('divisi.index', ['event_id' => $validated['event_id']])
            ->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function update(Request $request, Division $division)
    {
        $validated = $request->validate([
            'name'        => 'required|string|min:3|max:255',
            'description' => 'nullable|string',
            'event_id'    => 'required|exists:events,id',
        ]);

        $division->update($validated);

        return redirect()->route('divisi.index', ['event_id' => $validated['event_id']])
            ->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Division $division)
    {
        $eventId = $division->event_id;
        $division->delete();

        return redirect()->route('divisi.index', ['event_id' => $eventId])
            ->with('success', 'Divisi berhasil dihapus.');
    }

    /**
     * Export data divisi ke format CSV
     */
    public function export(Request $request)
    {
        $selectedEventId = $request->input('event_id');

        $query = Division::withCount('committeeMembers')
            ->where('event_id', $selectedEventId)
            ->latest();

        if ($request->filled('search_divisi')) {
            $query->where('name', 'like', '%' . $request->search_divisi . '%');
        }

        $divisiList = $query->get();

        // Ambil nama event untuk nama file
        $event = \App\Models\Event::find($selectedEventId);
        $eventName = $event ? preg_replace('/[^a-zA-Z0-9_]/', '_', $event->name) : 'event';

        $filename = 'data_divisi_' . $eventName . '_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($divisiList) {
            $file = fopen('php://output', 'w');

            // Header kolom
            fputcsv($file, ['No', 'Nama Divisi', 'Deskripsi', 'Jumlah Panitia']);

            foreach ($divisiList as $i => $divisi) {
                fputcsv($file, [
                    $i + 1,
                    $divisi->name,
                    $divisi->description ?? '-',
                    $divisi->committee_members_count,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
