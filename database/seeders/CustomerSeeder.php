<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        for ($i = 0; $i < 50; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $name = "$firstName $lastName";
            $email = strtolower("$firstName.$lastName") . '@' . $faker->freeEmailDomain;
            
            // Create user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
            ]);

            // Create customer
            Customer::create([
                'user_id' => $user->id,
                'name' => $name,
                'phone' => $faker->phoneNumber,
                'email' => $email,
                'address' => $faker->address,
                'segment' => $faker->randomElement(['premium', 'regular', 'new', 'vip']),
            ]);
        }

        $this->command->info('Successfully seeded 50 customers with users!');

    }
}
