<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EventManagementStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalEvents = Event::count();
        $publishedEvents = Event::where('status', 'published')->count();

        $totalBookings = Booking::count();
        $paidBookings = Booking::where('status', 'paid')->count();

        $totalTickets = Ticket::count();
        $checkedInTickets = Ticket::where('is_checked_in', true)->count();

        $totalRevenue = Booking::where('status', 'paid')->sum('final_amount');

        return [
            Stat::make('Total Events', $totalEvents)
                ->description($publishedEvents.' events published')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),

            Stat::make('Total Bookings', $totalBookings)
                ->description($paidBookings.' paid bookings')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('success'),

            Stat::make('Total Tickets', $totalTickets)
                ->description($checkedInTickets.' checked in')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('warning'),

            Stat::make('Total Revenue', 'Rp '.number_format($totalRevenue))
                ->description('From all paid bookings')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),
        ];
    }
}
