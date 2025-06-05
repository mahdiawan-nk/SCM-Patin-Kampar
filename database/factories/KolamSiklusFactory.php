<?php

namespace Database\Factories;

use App\Models\KolamBudidaya;
use App\Models\KolamSiklus;
use Illuminate\Database\Eloquent\Factories\Factory;

class KolamSiklusFactory extends Factory
{
    protected $model = KolamSiklus::class;

    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('-3 months', 'now');
        $initialStock = $this->faker->numberBetween(1000, 10000);
        $initialAvgWeight = $this->faker->randomFloat(2, 0.1, 5);
        $panjang = $this->faker->numberBetween(5, 50);
        $lebar = $this->faker->numberBetween(3, 30);
        $luas = $panjang * $lebar;
        $stockingDensity = $initialStock / $luas;

        return [
            'kolam_budidaya_id' => null,
            'strain' => $this->faker->randomElement([
                'Lele Sangkuriang',
                'Nila Gesit',
                'Patin Jumbo',
                'Gurame Soang',
                'Udang Vaname',
                'Bawal Bintang'
            ]),
            'start_date' => $startDate,
            'initial_stock' => $initialStock,
            'initial_avg_weight' => $initialAvgWeight,
            'stocking_density' => round($stockingDensity, 2),
            'status' => $this->faker->randomElement(['berjalan', 'selesai']),
        ];
    }

    public function berjalan()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'berjalan',
            ];
        });
    }

    public function selesai()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'selesai',
            ];
        });
    }
}
