<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        // Data KPI
        $activeEvents = Event::where('status', 'active')->count();
        $completedEvents = Event::where('status', 'completed')->count();
        $avgRating = Evaluation::avg('final_score') ?? 0;

        // Daftar Event
        $events = Event::latest()->paginate(10);

        return view('manajemen_event.index', compact(
            'activeEvents',
            'completedEvents',
            'avgRating',
            'events'
        ));
    }
}
