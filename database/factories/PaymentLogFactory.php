<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\PaymentLog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PaymentLogFactory extends Factory
{
    protected $model = PaymentLog::class;

    public function definition(): array
    {
        return [
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'processed_at' => Carbon::now(),
            'gateway_response' => $this->faker->words(),
            'status' => $this->faker->word(),
            'payment_method' => $this->faker->word(),
            'transaction_id' => $this->faker->word(),

            'booking_id' => Booking::factory(),
        ];
    }
}
