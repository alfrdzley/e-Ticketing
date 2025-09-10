<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ticket_code' => 'TICKET-'.strtoupper(Str::random(10)),
            'attendee_name' => $this->faker->name,
            'attendee_email' => $this->faker->safeEmail,
            'is_checked_in' => false,
        ];
    }
}
