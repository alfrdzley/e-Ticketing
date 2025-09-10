<?php

namespace Database\Seeders;

use App\Models\EventCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Konser Musik', 'Seminar Teknologi', 'Workshop Desain', 'Pameran Seni',
            'Festival Kuliner', 'Kompetisi Olahraga', 'Teater dan Pertunjukan',
            'Konferensi Bisnis', 'Acara Amal', 'Stand-up Comedy',
        ];

        foreach ($categories as $category) {
            EventCategory::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'is_active' => true,
            ]);
        }
    }
}
