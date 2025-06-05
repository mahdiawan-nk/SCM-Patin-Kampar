<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\CustomerFeedback;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class CustomerFeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Get all customer IDs
        $customerIds = Customer::pluck('id')->toArray();

        // Create 100 feedback entries
        for ($i = 0; $i < 100; $i++) {
            $isCustomer = $faker->boolean(70); // 70% chance of being from a registered customer

            if ($isCustomer && !empty($customerIds)) {
                $customerId = $faker->randomElement($customerIds);
                $customer = Customer::find($customerId);
                $name = $customer->name;
                $email = $customer->email;
            } else {
                $customerId = null;
                $name = $faker->name;
                $email = $faker->safeEmail;
            }

            $type = $faker->randomElement(['testimoni', 'keluhan', 'saran']);
            $submittedAt = $faker->dateTimeBetween('-6 months', 'now');

            // Determine status and related timestamps
            $status = $faker->randomElement(['baru', 'dibaca', 'ditanggapi']);
            $readAt = $status !== 'baru' ? $faker->dateTimeBetween($submittedAt, 'now') : null;
            $response = $status === 'ditanggapi' ? $faker->paragraph : null;

            CustomerFeedback::create([
                'uuid' => Str::uuid(),
                'customer_id' => $customerId,
                'type' => $type,
                'name' => $name,
                'email' => $email,
                'message' => $this->generateMessage($type, $faker),
                'submited_at' => $submittedAt,
                'status' => $status,
                'read_at' => $readAt,
                'response' => $response,
                'created_at' => $submittedAt,
                'updated_at' => $status === 'baru' ? $submittedAt : $faker->dateTimeBetween($submittedAt, 'now'),
            ]);
        }

        $this->command->info('Successfully seeded 100 customer feedbacks!');
    }

    /**
     * Generate appropriate message based on feedback type
     */
    protected function generateMessage($type, $faker)
    {
        switch ($type) {
            case 'testimoni':
                return $faker->randomElement([
                    'Pelayanan sangat memuaskan, saya akan kembali lagi!',
                    'Produk berkualitas tinggi, sangat recommended!',
                    'Pengalaman berbelanja yang menyenangkan, staff sangat ramah.',
                    'Pengiriman cepat dan packing aman, terima kasih!',
                    'Saya sangat puas dengan layanan yang diberikan.'
                ]);

            case 'keluhan':
                return $faker->randomElement([
                    'Produk yang saya terima rusak, mohon penjelasannya.',
                    'Pengiriman sangat lambat, sudah lewat dari estimasi.',
                    'Staff yang melayani kurang ramah dan terburu-buru.',
                    'Barang tidak sesuai dengan deskripsi di website.',
                    'Saya sudah menunggu lama tapi pesanan belum diproses.'
                ]);

            case 'saran':
                return $faker->randomElement([
                    'Mungkin bisa ditambahkan lebih banyak varian warna untuk produk ini.',
                    'Sebaiknya memperbaiki sistem notifikasi status pesanan.',
                    'Bagus jika ada opsi pengiriman yang lebih cepat.',
                    'Mungkin bisa buka cabang di daerah saya.',
                    'Sebaiknya ada diskon khusus untuk pelanggan setia.'
                ]);
        }
    }
}
