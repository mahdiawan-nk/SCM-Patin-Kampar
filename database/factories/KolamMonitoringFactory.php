<?php

namespace Database\Factories;

use App\Models\KolamBudidaya;
use App\Models\KolamMonitoring;
use Illuminate\Database\Eloquent\Factories\Factory;

class KolamMonitoringFactory extends Factory
{
    protected $model = KolamMonitoring::class;

    public function definition()
    {
        // Rentang waktu monitoring (dari 1 bulan yang lalu sampai sekarang)
        $monitoringDate = $this->faker->dateTimeBetween('-1 month', 'now');

        return [
            'kolam_budidaya_id' => null,
            'tgl_monitoring' => $monitoringDate,
            'temperature' => $this->faker->randomFloat(2, 25, 32), // Suhu air budidaya umumnya 25-32°C
            'ph' => $this->faker->randomFloat(2, 6.5, 8.5), // pH ideal untuk budidaya 6.5-8.5
            'do' => $this->faker->randomFloat(2, 3, 8), // Oksigen terlarut 3-8 mg/L
            'tds' => $this->faker->numberBetween(100, 2000), // TDS 100-2000 ppm
            'turbidity' => $this->faker->randomFloat(2, 0, 50), // Turbidity 0-50 NTU
            'humidity' => $this->faker->numberBetween(60, 95), // Kelembapan 60-95%
            'brightness' => $this->faker->randomFloat(2, 20, 60), // Kecerahan 20-60 cm
            'amonia' => $this->faker->randomFloat(2, 0, 1.5), // Amonia 0-1.5 mg/L
            'nitrite' => $this->faker->randomFloat(2, 0, 0.5), // Nitrit 0-0.5 mg/L
            'nitrate' => $this->faker->randomFloat(2, 0, 50), // Nitrat 0-50 mg/L
        ];
    }
}
