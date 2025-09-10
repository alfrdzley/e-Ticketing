<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            'Wellness', 'Startup', 'Design', 'AI', 'Coffee', 'Surf',
            'Travel', 'Photography', 'Music', 'Culinary', 'Craft',
            'Tech', 'Sustainability', 'Marketing', 'Product', 'Hospitality',
            'Public Speaking', 'Developer', 'Blockchain', 'UX/UI',
        ];

        $eventNouns = [
            'Festival', 'Summit', 'Workshop', 'Conference', 'Meetup',
            'Expo', 'Camp', 'Forum', 'Symposium', 'Showcase',
            'Retreat', 'Bootcamp', 'Hackathon', 'Fair', 'Talks',
            'Community Day', 'Open House', 'Creators Night', 'Market', 'Gathering',
        ];

        $cities = [
            ['Canggu', 'Bali', 'Indonesia', -8.6478, 115.1385],
            ['Jakarta', 'DKI Jakarta', 'Indonesia', -6.2088, 106.8456],
            ['Bandung', 'West Java', 'Indonesia', -6.9175, 107.6191],
            ['Surabaya', 'East Java', 'Indonesia', -7.2575, 112.7521],
            ['Yogyakarta', 'Yogyakarta', 'Indonesia', -7.7956, 110.3695],
            ['Makassar', 'South Sulawesi', 'Indonesia', -5.1477, 119.4327],
            ['Denpasar', 'Bali', 'Indonesia', -8.6705, 115.2126],
            ['Semarang', 'Central Java', 'Indonesia', -6.9667, 110.4167],
            ['Medan', 'North Sumatra', 'Indonesia', 3.5952, 98.6722],
            ['Bali', 'Bali', 'Indonesia', -8.4095, 115.1889],
        ];

        $organizers = [
            ['Leafy Lofts Canggu', 'hello@leafylofts.example', '+62-811-0000-001'],
            ['Leafy Home Canggu', 'stay@leafyhome.example', '+62-811-0000-002'],
            ['Speaktacular.id', 'hi@speaktacular.example', '+62-811-0000-003'],
            ['Bali Creators Club', 'contact@balicreators.example', '+62-811-0000-004'],
            ['Makassar Tech Hub', 'info@makassartech.example', '+62-811-0000-005'],
            ['Bandung Startup Lab', 'team@bdgstartuplab.example', '+62-811-0000-006'],
        ];

        $events = [];

        for ($i = 1; $i <= 50; $i++) {
            $theme = $themes[array_rand($themes)];
            $noun = $eventNouns[array_rand($eventNouns)];
            $cityData = $cities[array_rand($cities)];
            $organizer = $organizers[array_rand($organizers)];

            $name = "$theme $noun ".(rand(0, 3) ? 2025 : 2026);
            $slug = Str::slug($name)."-$i";

            $start = Carbon::create(2025, 9, 1)->addDays(rand(0, 150))->setTime(rand(9, 18), 0);
            $end = (clone $start)->addDays(rand(1, 3))->addHours(rand(4, 8));

            $price = rand(0, 100) < 20 ? 0 : collect([50000, 75000, 100000, 150000, 250000, 350000])->random();
            $quota = collect([50, 75, 100, 120, 150, 200, 250])->random();

            $events[] = [
                'name' => $name,
                'slug' => $slug,
                'description' => "$name is a curated experience bringing together enthusiasts and professionals in ".strtolower($theme).". Expect hands-on sessions, networking, and local vibes in {$cityData[0]}.",
                'banner_image_url' => 'https://source.unsplash.com/1600x900/?event,'.strtolower(str_replace(' ', '', $theme)),
                'start_date' => $start,
                'end_date' => $end,
                'location' => rand(0, 100) < 25 ? 'Online' : 'Onsite',
                'address' => "{$cityData[0]} Convention Center",
                'price' => $price,
                'quota' => $quota,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('events')->insert($events);
    }
}
