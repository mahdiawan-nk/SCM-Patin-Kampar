<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class SumaryDashboard extends Widget
{
    protected static string $view = 'filament.widgets.sumary-dashboard';

    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        return [
            'totalPanen' => [
                'harian' => fake()->numberBetween(100, 500),
                'mingguan' => fake()->numberBetween(1000, 3000),
                'bulanan' => fake()->numberBetween(5000, 15000),
            ],
            'pendapatan' => [
                'harian' => fake()->randomFloat(2, 500000, 2000000),
                'bulanan' => fake()->randomFloat(2, 20000000, 100000000),
            ],
            'produkTerlaris' => collect([
                ['produk' => 'Ikan Segar', 'jumlah' => fake()->numberBetween(100, 500)],
                ['produk' => 'Fillet', 'jumlah' => fake()->numberBetween(50, 300)],
                ['produk' => 'Nugget', 'jumlah' => fake()->numberBetween(30, 200)],
            ])->sortByDesc('jumlah')->values(),
            'pengiriman' => [
                'dalamProses' => fake()->numberBetween(1, 10),
                'armadaTersedia' => fake()->numberBetween(3, 10),
                'armadaTidakTersedia' => fake()->numberBetween(0, 3),
            ],
        ];
    }
}
