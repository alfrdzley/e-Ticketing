<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 4);
        $unitPrice = $this->faker->randomElement([50000, 75000, 100000, 150000, 200000]);
        $totalAmount = $unitPrice * $quantity;

        return [
            'booking_code' => 'BOOK-' . strtoupper(Str::random(8)),
            'user_id' => User::factory(),
            'event_id' => Event::factory(),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_amount' => $totalAmount,
            'final_amount' => $totalAmount,
            'status' => $this->faker->randomElement(['confirmed', 'pending', 'cancelled']),
            'booker_name' => $this->faker->name,
            'booker_email' => $this->faker->safeEmail,
            'booker_phone' => $this->faker->phoneNumber,
            'booking_date' => now(),
        ];
    }
}


