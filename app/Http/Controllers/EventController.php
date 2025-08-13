<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of published events with upcoming dates
     */
    public function index()
    {
        $events = Event::where('status', 'published')
            ->where('start_date', '>=', Carbon::now())
            ->orderBy('start_date', 'asc')
            ->get();
        
        return view('events.index', compact('events'));
    }

    /**
     * Display the specified event with full details
     */
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }
}
