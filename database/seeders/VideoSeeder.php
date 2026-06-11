<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kimetsuVideo = Category::where('name', 'Kimetsu no Yaiba')->first();
        $yuukokuVideo = Category::where('name', 'Yuukoku no Moriarty')->first();
        $chainsawVideo = Category::where('name', 'Chainsaw Man')->first();

        $videos = [
            [
                'category_id' => $kimetsuVideo?->id,
                'title' => 'Kimetsu no Yaiba Season 1',
                'thumbnail' => 'thumbnails/sample1.jpg',
                'description' => 'Tonton petualangan awal Tanjiro Kamado dan Nezuko dalam membasmi iblis di Kimetsu no Yaiba Season 1.',
                'video_path' => 'videos/sample1.mp4',
            ],
            [
                'category_id' => $kimetsuVideo?->id,
                'title' => 'Kimetsu no Yaiba Season 2',
                'thumbnail' => 'thumbnails/sample2.jpg',
                'description' => 'Kelanjutan kisah Tanjiro di Entertainment District Arc yang penuh pertarungan sengit di Kimetsu no Yaiba Season 2.',
                'video_path' => 'videos/sample2.mp4',
            ],
            [
                'category_id' => $kimetsuVideo?->id,
                'title' => 'Kimetsu no Yaiba Season 3',
                'thumbnail' => 'thumbnails/sample3.jpg',
                'description' => 'Aksi mendebarkan di Swordsmith Village Arc bersama para Hashira di Kimetsu no Yaiba Season 3.',
                'video_path' => 'videos/sample3.mp4',
            ],
            [
                'category_id' => $yuukokuVideo?->id,
                'title' => 'Yuukoku no Moriarty Season 1',
                'thumbnail' => 'thumbnails/sample4.jpg',
                'description' => 'Kisah William James Moriarty dalam membongkar kebobrokan sistem kelas sosial di Inggris pada Yuukoku no Moriarty Season 1.',
                'video_path' => 'videos/sample4.mp4',
            ],
            [
                'category_id' => $yuukokuVideo?->id,
                'title' => 'Yuukoku no Moriarty Season 2',
                'thumbnail' => 'thumbnails/sample5.jpg',
                'description' => 'Pertarungan kecerdasan klimaks antara sang konsultan kejahatan Moriarty dengan detektif Sherlock Holmes di Season 2.',
                'video_path' => 'videos/sample5.mp4',
            ],
            [
                'category_id' => $chainsawVideo?->id,
                'title' => 'Chainsaw Man',
                'thumbnail' => 'thumbnails/sample6.webp',
                'description' => 'Kisah Denji, seorang pemuda miskin yang bertransformasi menjadi Public Safety Devil Hunter berkekuatan gergaji mesin setelah bersatu dengan Pochita.',
                'video_path' => 'videos/sample6.mp4',
            ],
        ];


        if (Video::count() === 0) {
            foreach ($videos as $videoData) {
                Video::create($videoData);
            }
        }
    }
}
