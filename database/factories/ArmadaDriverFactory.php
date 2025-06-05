<?php

namespace Database\Factories;

use App\Models\ArmadaDriver;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArmadaDriverFactory extends Factory
{
    protected $model = ArmadaDriver::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->unique()->phoneNumber,
            'license_no' => 'SIM' . $this->faker->unique()->numerify('########'),
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }
}
