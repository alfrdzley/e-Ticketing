<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display user dashboard
     */
    public function index()
    {
        $user = Auth::user();
        try {
            $bookings = $user->bookings()
                ->with(['event' => function ($query) {
                    $query->select('id', 'name', 'start_date', 'location');
                }])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            $allBookings = $user->bookings();
            $stats = [
                'total_bookings' => $user->bookings()->count(),
                'confirmed_bookings' => $user->bookings()->whereIn('status', ['paid', 'confirmed'])->count(),
                'pending_bookings' => $user->bookings()->where('status', 'pending')->count(),
                'cancelled_bookings' => $user->bookings()->where('status', 'cancelled')->count(),
                'total_spent' => $user->bookings()->whereIn('status', ['paid', 'confirmed', 'completed'])->sum('final_amount') ?? 0,
            ];

            return view('dashboard', data: compact('user', 'bookings', 'stats'));

        } catch (Exception $e) {
            $stats = [
                'total_bookings' => 0,
                'confirmed_bookings' => 0,
                'pending_bookings' => 0,
                'cancelled_bookings' => 0,
                'total_spent' => 0,
            ];

            $bookings = new LengthAwarePaginator(
                collect([]),
                0,
                10,
                1,
                ['path' => request()->url()]
            );

            return view('dashboard', compact('user', 'bookings', 'stats'))
                ->with('error', 'There was an issue loading your dashboard data: '.$e->getMessage());
        }
    }
}
