<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProductionInputSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Sample data specific to Patin fish production
        $sources = [
            'panen' => [
                'Kolam A1',
                'Kolam B2',
                'Kolam C3',
                'Kolam D4',
                'Kolam E5',
                'Tambak Utara',
                'Tambak Selatan'
            ],
            'stok' => [
                'Gudang Dingin Utama',
                'Gudang Penyimpanan 1',
                'Cold Storage Pusat',
                'Sementara Hatchery'
            ],
            'lainnya' => [
                'Pembelian Petani Lokal',
                'Impor Vietnam',
                'Kerjasama Budidaya',
                'Bantuan Pemerintah'
            ]
        ];

        // Create 50 production inputs
        for ($i = 0; $i < 50; $i++) {
            $sourceType = $faker->randomElement(['panen', 'stok', 'lainnya']);
            $sourceName = $faker->randomElement($sources[$sourceType]);
            $productionDate = $faker->dateTimeBetween('-1 year', 'now');

            // Weight range varies by source type
            $weight = match ($sourceType) {
                'panen' => $faker->randomFloat(2, 50, 500), // Typical harvest weight 50-500kg
                'stok' => $faker->randomFloat(2, 100, 1000), // Larger quantities in storage
                'lainnya' => $faker->randomFloat(2, 20, 300), // Smaller quantities for other sources
            };

            DB::table('production_inputs')->insert([
                'uuid' => Str::uuid(),
                'production_date' => $productionDate,
                'source_type' => $sourceType,
                'source_name' => $sourceName,
                'total_weight_kg' => $weight,
                'note' => $this->generateNote($sourceType, $faker),
                'created_at' => $productionDate,
                'updated_at' => $faker->dateTimeBetween($productionDate, 'now'),
            ]);
        }

        $this->command->info('Successfully seeded 50 production inputs for SCM Patin!');
    }

    /**
     * Generate realistic notes based on source type
     */
    protected function generateNote($sourceType, $faker)
    {
        switch ($sourceType) {
            case 'panen':
                return $faker->randomElement([
                    'Panen rutin mingguan kolam',
                    'Panen khusus permintaan pembeli',
                    'Panen akibat perubahan cuaca',
                    'Kualitas ikan baik, ukuran seragam',
                    'Ada sedikit kendala jaring rusak saat panen'
                ]);

            case 'stok':
                return $faker->randomElement([
                    'Pemindahan stok dari gudang lama',
                    'Stok awal bulan',
                    'Penyortiran stok untuk quality control',
                    'Persiapan untuk pengiriman besar',
                    'Stok darurat untuk permintaan mendadak'
                ]);

            case 'lainnya':
                return $faker->randomElement([
                    'Pembelian dari kelompok tani binaan',
                    'Import kualitas premium',
                    'Bantuan bibit untuk program pemerintah',
                    'Kerjasama dengan petani mitra',
                    'Titipan olahan dari unit pengolahan'
                ]);
        }
    }
}
