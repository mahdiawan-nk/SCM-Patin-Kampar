<?php

namespace Database\Factories;

use App\Models\DeliveryTracking;
use App\Models\Delivery;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryTrackingFactory extends Factory
{
    protected $model = DeliveryTracking::class;

    public function definition(): array
    {
        $statuses = [
            DeliveryTracking::STATUS_MULAI,
            DeliveryTracking::STATUS_DALAM_PERJALANAN,
            DeliveryTracking::STATUS_TIBA,
            DeliveryTracking::STATUS_TERLAMBAT
        ];

        return [
            'delivery_id' => Delivery::factory(),
            'status' => $this->faker->randomElement($statuses),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'timestamp' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'note' => $this->faker->optional()->sentence,
        ];
    }
}