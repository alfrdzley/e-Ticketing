<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class EventController extends Controller
{
    /**
     * Display a listing of published events with upcoming dates
     */
    public function index()
    {
        $events = Cache::remember('events.index', 600, function () {
            return Event::where('status', 'published')
                ->where('start_date', '>=', Carbon::now())
                ->orderBy('start_date', 'asc')
                ->get();
        });

        return view('events.index', compact('events'));
    }

    /**`
     * Display the specified event with full details
     */
    public function show(Event $event)
    {
        // $event = Cache::remember("events.show.{$event->id}", 600, function () use ($event) {
        //     return $event->load('details');
        // });

        return view('events.show', compact('event'));
    }
}
