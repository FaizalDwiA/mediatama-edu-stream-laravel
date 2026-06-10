<?php

namespace App\Filament\Widgets;

use App\Models\Video;
use App\Models\AccessRequest;
use App\Models\User;
use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VideoOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        return [
            // KARTU 1: TOTAL VIDEO (Tanpa deskripsi)
            Stat::make('Total Koleksi Video', Video::count() . ' Video')
                ->chart([7, 3, 5, 2, 10, 6, Video::count()])
                ->color('success'),

            // KARTU 2: PERMINTAAN AKSES (Tanpa deskripsi)
            Stat::make('Permintaan Akses', AccessRequest::count() . ' Antrean')
                ->chart([1, 5, 2, 8, 4, 3, AccessRequest::count()])
                ->color(AccessRequest::count() > 0 ? 'danger' : 'gray'),

            // KARTU 3: TOTAL PENGGUNA (Tanpa deskripsi)
            Stat::make('Total Pengguna', User::count() . ' Akun')
                ->color('info'),

            // KARTU 4: TOTAL KATEGORI (Tanpa deskripsi)
            Stat::make('Kategori Konten', Category::count() . ' Genre')
                ->color('warning'),
        ];
    }
}
