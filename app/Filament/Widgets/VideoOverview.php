<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VideoOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            \Filament\Widgets\StatsOverviewWidget\Stat::make('Total Video', \App\Models\Video::count()),
            \Filament\Widgets\StatsOverviewWidget\Stat::make('Permintaan Akses', \App\Models\AccessRequest::count()),
        ];
    }
}
