<?php

namespace Database\Factories;

use App\Models\Pembudidaya;
use App\Models\PembudidayaUsaha;
use Illuminate\Database\Eloquent\Factories\Factory;

class PembudidayaUsahaFactory extends Factory
{
    protected $model = PembudidayaUsaha::class;

    public function definition()
    {
        $this->faker->locale('id_ID'); // Set locale ke Indonesia

        $jenisUsaha = [
            'Pembenihan Ikan Lele',
            'Pembesaran Ikan Nila',
            'Budidaya Udang Vaname',
            'Pembenihan Gurame',
            'Budidaya Patin',
            'Rumput Laut',
            'Keramba Jaring Apung',
            'Budidaya Ikan Hias'
        ];

        $sistemBudidaya = [
            'Intensif',
            'Semi Intensif',
            'Tradisional',
            'Bioflok',
            'Akuaponik',
            'Keramba'
        ];

        $provinsi = [
            'Jawa Barat',
            'Jawa Tengah',
            'Jawa Timur',
            'Sumatera Utara',
            'Sumatera Selatan',
            'Kalimantan Barat',
            'Sulawesi Selatan',
            'Bali'
        ];

        return [
            'pembudidaya_id' => Pembudidaya::factory(),
            'nama_usaha' => 'UD. ' . $this->faker->company,
            'jenis_usaha' => $this->faker->randomElement($jenisUsaha),
            'luas_lahan' => $this->faker->numberBetween(100, 10000),
            'jumlah_kolam' => $this->faker->numberBetween(1, 20),
            'sistem_budidaya' => $this->faker->randomElement($sistemBudidaya),
            'jenis_izin_usaha' => $this->faker->randomElement(['SIUP', 'IUMK', 'NIB', 'Belum Ada']),
            'tahun_mulai_usaha' => $this->faker->numberBetween(2000, date('Y')),
            'status_kepemilikan_usaha' => $this->faker->randomElement(['Milik Sendiri', 'Sewa', 'Kelompok']),
            'nama_kelompok' => $this->faker->optional(0.7)->passthrough('Kelompok ' . $this->faker->lastName),
            'jabatan_di_kelompok' => $this->faker->optional(0.5)->randomElement(['Ketua', 'Sekretaris', 'Bendahara', 'Anggota']),
            'provinsi' => $this->faker->randomElement($provinsi),
            'kabupaten' => $this->faker->city,
            'kecamatan' => 'Kec. ' . $this->faker->citySuffix,
            'desa' => 'Desa ' . $this->faker->streetName,
            'alamat_usaha' => $this->faker->address,
            'kordinat' => json_encode([
                'lat' => $this->faker->latitude(-6.8, -5.9),
                'lng' => $this->faker->longitude(105.5, 110.5),
            ]),
            'no_izin_usaha' => $this->faker->optional(0.6)->bothify('IZIN-####/###/DJPB'),
            'ktp_scan' => $this->faker->optional(0.8)->passthrough('https://example.com/ktp/' . $this->faker->uuid . '.jpg'),
            'foto_lokasi' => $this->faker->optional(0.9)->passthrough('https://example.com/lokasi/' . $this->faker->uuid . '.jpg'),
            'surat_izin' => $this->faker->optional(0.5)->passthrough('https://example.com/izin/' . $this->faker->uuid . '.pdf'),
        ];
    }
}
