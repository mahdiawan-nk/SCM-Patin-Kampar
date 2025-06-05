<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatCard extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pembudidaya', '25'),
            Stat::make('Kolam', '35'),
            Stat::make('Buyer', '15'),
            Stat::make('User', '5'),
        ];
    }
}
