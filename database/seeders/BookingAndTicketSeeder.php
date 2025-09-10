<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class BookingAndTicketSeeder extends Seeder
{
    public function run(): void
    {
        Booking::factory()->count(100)->create()->each(function ($booking) {
            for ($i = 0; $i < $booking->quantity; $i++) {
                Ticket::factory()->create([
                    'booking_id' => $booking->id,
                ]);
            }
        });
    }
}
