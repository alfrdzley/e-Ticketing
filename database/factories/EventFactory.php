<?php

namespace Database\Factories;

use App\Models\EventCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->sentence(3);
        $startDate = $this->faker->dateTimeBetween('+1 week', '+3 months');
        $endDate = $this->faker->dateTimeBetween($startDate, (clone $startDate)->modify('+5 hours'));

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(5),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => $this->faker->city.' Convention Hall',
            'address' => $this->faker->address,
            'price' => $this->faker->randomElement([50000, 75000, 100000, 150000, 200000]),
            'quota' => $this->faker->numberBetween(100, 500),
            'status' => $this->faker->randomElement(['published', 'draft']),
            'category_id' => EventCategory::factory(),
            'organizer_id' => User::factory(),
        ];
    }
}
