<?php

namespace Database\Factories;

use App\Models\ArmadaVehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArmadaVehicleFactory extends Factory
{
    protected $model = ArmadaVehicle::class;

    public function definition(): array
    {
        $types = [
            ArmadaVehicle::TYPE_TRUCK,
            ArmadaVehicle::TYPE_PICKUP,
            ArmadaVehicle::TYPE_VAN,
            ArmadaVehicle::TYPE_MOTORCYCLE
        ];

        return [
            'plate_number' => strtoupper(fake()->bothify('??####??')),
            'vehicle_type' => fake()->randomElement($types),
            'capacity_kg' => fake()->randomFloat(2, 500, 5000),
            'is_active' => fake()->boolean(80),
        ];
    }
}
