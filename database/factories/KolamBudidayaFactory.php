<?php

namespace Database\Factories;

use App\Models\KolamBudidaya;
use App\Models\Pembudidaya;
use Illuminate\Database\Eloquent\Factories\Factory;

class KolamBudidayaFactory extends Factory
{
    protected $model = KolamBudidaya::class;

    public function definition()
    {
        $panjang = $this->faker->numberBetween(5, 50);
        $lebar = $this->faker->numberBetween(3, 30);
        $kedalaman = $this->faker->numberBetween(1, 5);
        $volume = $panjang * $lebar * $kedalaman;
        $kapasitas = $volume * $this->faker->numberBetween(5, 20); // Kepadatan tebar per m3

        return [
            'pembudidaya_id' => rand(1, Pembudidaya::count()),
            'nama_kolam' => 'Kolam ' . $this->faker->randomElement(['A', 'B', 'C', 'D']) . '-' . $this->faker->numberBetween(1, 10),
            'lokasi_kolam' => $this->faker->randomElement(['Blok Utara', 'Blok Selatan', 'Blok Timur', 'Blok Barat']),
            'panjang' => $panjang,
            'lebar' => $lebar,
            'kedalaman' => $kedalaman,
            'volume_air' => $volume,
            'kapasitas' => $kapasitas,
            'jenis_kolam' => $this->faker->randomElement(['tanah', 'terpal', 'beton', 'keramba']),
            'status' => $this->faker->randomElement(['aktif', 'maintenance', 'tidak aktif']),
        ];
    }
}
