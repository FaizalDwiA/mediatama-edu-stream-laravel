<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class VideoCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Video per Kategori';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Mengambil semua kategori beserta hitungan jumlah videonya
        $categories = Category::withCount('videos')->get();

        // Pisahkan nama kategori untuk label grafik (Sumbu X)
        $labels = $categories->pluck('name')->toArray();

        // Pisahkan angka jumlah video untuk data grafik (Sumbu Y)
        $data = $categories->pluck('videos_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Video',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Bisa diganti ke 'doughnut' atau 'pie' sesuai pilihan Anda di terminal tadi
    }
}
