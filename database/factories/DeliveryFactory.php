<?php

namespace Database\Factories;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\ArmadaDriver;
use App\Models\ArmadaVehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryFactory extends Factory
{
    protected $model = Delivery::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'delivery_type' => $this->faker->randomElement([
                Delivery::TYPE_INTERNAL,
                Delivery::TYPE_EKSTERNAL
            ]),
            'delivery_date' => $this->faker->dateTimeBetween('now', '+1 week'),
            'estimated_arrival' => $this->faker->dateTimeBetween('+1 day', '+2 weeks'),
            'actual_arrival' => $this->faker->optional(0.3)->dateTimeBetween('+1 day', '+2 weeks'),
            'status' => $this->faker->randomElement([
                Delivery::STATUS_DIPROSES,
                Delivery::STATUS_DIKIRIM,
                Delivery::STATUS_TIBA,
                Delivery::STATUS_GAGAL
            ]),
            'driver_id' => ArmadaDriver::factory(),
            'vehicle_id' => ArmadaVehicle::factory(),
            'courier_service' => $this->faker->optional()->company,
            'tracking_number' => $this->faker->optional()->bothify('??##########??'),
            'tracking_url' => $this->faker->optional()->url,
            'note' => $this->faker->optional()->sentence,
        ];
    }
}