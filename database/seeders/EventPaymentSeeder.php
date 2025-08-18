<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = \App\Models\Event::all();

        foreach ($events as $event) {
            $event->update([
                'payment_account_name' => 'Event Organizer Indonesia',
                'payment_account_number' => '1234567890',
                'payment_bank_name' => 'Bank BNI',
                'payment_instructions' => 'Transfer ke rekening di atas dengan berita transfer: BOOKING-[BOOKING_CODE]. Upload bukti transfer setelah melakukan pembayaran.'
            ]);
        }
    }
}
