<?php

namespace Database\Factories;

use App\Models\DistribusiSchedule;
use App\Models\Delivery;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class DistribusiScheduleFactory extends Factory
{
    protected $model = DistribusiSchedule::class;

    public function definition(): array
    {
        $startHour = $this->faker->numberBetween(8, 16);
        $endHour = $startHour + $this->faker->numberBetween(1, 4);

        return [
            'delivery_id' => Delivery::factory(),
            'scheduled_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'time_window' => sprintf("%02d:00-%02d:00", $startHour, $endHour),
            'status' => $this->faker->randomElement([
                DistribusiSchedule::STATUS_DIJADWALKAN,
                DistribusiSchedule::STATUS_BERLANGSUNG,
                DistribusiSchedule::STATUS_SELESAI
            ]),
            'note' => $this->faker->optional()->sentence,
        ];
    }
}
