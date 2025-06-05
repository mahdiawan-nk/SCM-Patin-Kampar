<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class ProductionDemandChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Produksi vs Permintaan';
    protected static ?int $sort = 3;
    protected function getData(): array
    {
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];

        $produksi = collect($labels)->map(fn() => fake()->numberBetween(3000, 8000))->toArray();
        $permintaan = collect($labels)->map(fn() => fake()->numberBetween(4000, 9000))->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Produksi (kg)',
                    'data' => $produksi,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.7)',
                ],
                [
                    'label' => 'Permintaan (kg)',
                    'data' => $permintaan,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.7)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => ['display' => true, 'text' => 'Kg'],
                ],
                'x' => [
                    'title' => ['display' => true, 'text' => 'Bulan'],
                ],
            ],
        ];
    }
    protected function getType(): string
    {
        return 'bar';
    }
}
