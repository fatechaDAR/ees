<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        // Data KPI
        $activeEvents = Event::where('admin_id', auth()->id())->where('status', 'active')->count();
        $completedEvents = Event::where('admin_id', auth()->id())->where('status', 'completed')->count();
        $avgRating = Evaluation::whereHas('event', function ($q) {
            $q->where('admin_id', auth()->id());
        })->avg('final_score') ?? 0;

        // Daftar Event dengan filter
        $query = Event::where('admin_id', auth()->id())->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->paginate(10)->withQueryString();

        return view('manajemen_event.index', compact(
            'activeEvents',
            'completedEvents',
            'avgRating',
            'events'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'nullable|date',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed',
        ]);

        Event::create([
            'name' => $validated['name'],
            'start_date' => $validated['date'] ?? null,
            'end_date' => $validated['date'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'admin_id' => auth()->id(),
        ]);

        return redirect()->route('event.index')->with('success', 'Event berhasil ditambahkan.');
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'nullable|date',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed',
        ]);

        $event->update([
            'name' => $validated['name'],
            'start_date' => $validated['date'] ?? null,
            'end_date' => $validated['date'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('event.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('event.index')->with('success', 'Event berhasil dihapus.');
    }

    /**
     * Export data event ke format CSV
     */
    public function export(Request $request)
    {
        $query = Event::where('admin_id', auth()->id())->latest();

        // Ikut serta filter yang aktif (kalau ada)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->get();

        $filename = 'data_event_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($events) {
            $file = fopen('php://output', 'w');

            // Header kolom
            fputcsv($file, ['No', 'Nama Event', 'Deskripsi', 'Tanggal Mulai', 'Tanggal Selesai', 'Status']);

            foreach ($events as $i => $event) {
                fputcsv($file, [
                    $i + 1,
                    $event->name,
                    $event->description ?? '-',
                    $event->start_date ?? '-',
                    $event->end_date ?? '-',
                    ucfirst($event->status),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
