<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'order_number' => 'ORD' . $this->faker->unique()->numerify('########'),
            'customer_id' => rand(1, Customer::count()),
            'status' => $this->faker->randomElement([
                'pending',
                'diproses',
                'dikirim',
                'selesai',
                'batal'
            ]),
            'total_amount' => $this->faker->randomFloat(2, 100000, 10000000),
            'note' => $this->faker->optional()->sentence,
        ];
    }
}
