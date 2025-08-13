<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    /**
     * Display user dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Debug logging
        Log::info('Dashboard accessed', [
            'user_id' => $user ? $user->id : 'null',
            'user_name' => $user ? $user->name : 'null'
        ]);
        
        if (!$user) {
            Log::error('Dashboard: User not authenticated');
            abort(401, 'User not authenticated');
        }
        
        try {
            // Let's first check if user bookings relationship works
            Log::info('Dashboard: Checking user relationships', [
                'user_id' => $user->id,
                'bookings_count' => $user->bookings()->count()
            ]);
            
            $bookings = $user->bookings()
                ->with(['event' => function($query) {
                    $query->select('id', 'name', 'start_date', 'location');
                }])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                
            Log::info('Dashboard: Bookings loaded', [
                'count' => $bookings->count(),
                'total' => $bookings->total(),
                'items' => $bookings->items() ? $bookings->items() : 'empty'
            ]);
            
            $allBookings = $user->bookings();
            
            // Calculate statistics
            $stats = [
                'total_bookings' => $user->bookings()->count(),
                'confirmed_bookings' => $user->bookings()->whereIn('status', ['paid', 'confirmed'])->count(),
                'pending_bookings' => $user->bookings()->where('status', 'pending')->count(),
                'cancelled_bookings' => $user->bookings()->where('status', 'cancelled')->count(),
                'total_spent' => $user->bookings()->whereIn('status', ['paid', 'confirmed', 'completed'])->sum('final_amount') ?? 0,
            ];
            
            Log::info('Dashboard: Stats calculated', $stats);
            
            return view('dashboard', data: compact('user', 'bookings', 'stats'));
            
        } catch (Exception $e) {
            Log::error('Dashboard: Error loading data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
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
                ->with('error', 'There was an issue loading your dashboard data: ' . $e->getMessage());
        }
    }
}
