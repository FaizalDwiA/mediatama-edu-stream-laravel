<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $animeList = [
            'Kimetsu no Yaiba',
            'Yuukoku no Moriarty',
            'Chainsaw Man',
            'Spy x Family',
            'Sousou no Frieren',
            'Boku no Hero Academia',
            'Haikyuu!!',
            'Dr. Stone',
            'Jujutsu Kaisen',
            'Kono Oto Tomare!',
            'Shingeki no Kyojin',
            'Violet Evergarden',
            'Natsume Yuujinchou'
        ];

        foreach ($animeList as $anime) {
            Category::firstOrCreate([
                'name' => $anime,
            ]);
        }
    }
}
