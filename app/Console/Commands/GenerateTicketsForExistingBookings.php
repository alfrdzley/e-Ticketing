<?php

namespace App\Console\Commands;

use Exception;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Console\Command;

class GenerateTicketsForExistingBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:generate-for-existing-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate tickets for existing bookings that don\'t have tickets yet';

    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        parent::__construct();
        $this->bookingService = $bookingService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating tickets for existing bookings...');

        // Get all bookings that don't have tickets yet
        $bookingsWithoutTickets = Booking::whereDoesntHave('tickets')->get();

        if ($bookingsWithoutTickets->isEmpty()) {
            $this->info('No bookings found without tickets.');
            return;
        }

        $count = 0;
        foreach ($bookingsWithoutTickets as $booking) {
            $this->info("Generating tickets for booking: {$booking->booking_code}");
            
            try {
                $this->bookingService->generateTickets($booking);
                $count++;
                $this->line("✓ Generated {$booking->quantity} ticket(s) for {$booking->booking_code}");
            } catch (Exception $e) {
                $this->error("✗ Failed to generate tickets for {$booking->booking_code}: " . $e->getMessage());
            }
        }

        $this->info("Successfully generated tickets for {$count} bookings.");
    }
}
