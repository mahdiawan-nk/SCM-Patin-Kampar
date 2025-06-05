<?php

namespace Database\Factories;

use App\Models\Pembudidaya;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PembudidayaFactory extends Factory
{
    protected $model = Pembudidaya::class;

    public function definition()
    {
        // Set locale ke Indonesia
        $this->faker->locale('id_ID');

        $gender = $this->faker->randomElement(['Laki-laki', 'Perempuan']);
        $currentYear = date('Y');

        return [
            'nama_lengkap' => $this->faker->name($gender),
            'nik' => $this->generateNIK(),
            'jenis_kelamin' => $gender,
            'tanggal_lahir' => $this->faker->dateTimeBetween('-60 years', '-20 years'),
            'email' => $this->faker->unique()->safeEmail,
            'no_hp' => $this->generateIndonesianPhoneNumber(),
            'alamat_lengkap' => $this->faker->address,
            'status' => $this->faker->randomElement(['aktif', 'tidak aktif']),
            'tgl_bergabung' => $this->faker->dateTimeBetween('-5 years', 'now'),
        ];
    }

    /**
     * Generate valid Indonesian NIK (16 digits)
     */
    protected function generateNIK(): string
    {
        $provinceCode = $this->faker->numberBetween(11, 94); // Kode provinsi Indonesia (2 digit)
        $regencyCode = $this->faker->numberBetween(1, 99);   // Kode kabupaten/kota (2 digit)
        $districtCode = $this->faker->numberBetween(1, 99);  // Kode kecamatan (2 digit)
        $birthDate = $this->faker->dateTimeBetween('-60 years', '-20 years');

        // Format: PPKKDDddmmyyXXXX
        return sprintf(
            '%02d%02d%02d%02d%02d%02d%04d',
            $provinceCode,                      // 2 digit kode provinsi
            $regencyCode,                       // 2 digit kode kabupaten/kota
            $districtCode,                      // 2 digit kode kecamatan
            $birthDate->format('d'),            // 2 digit tanggal lahir
            $birthDate->format('m'),            // 2 digit bulan lahir
            $birthDate->format('y'),            // 2 digit tahun lahir (2 digit terakhir)
            $this->faker->numberBetween(1, 9999) // 4 digit nomor urut
        );
    }

    /**
     * Generate Indonesian phone number
     */
    protected function generateIndonesianPhoneNumber(): string
    {
        $prefixes = [
            '0812',
            '0813',
            '0814',
            '0815',
            '0816',
            '0817',
            '0818',
            '0819',
            '0821',
            '0822',
            '0823',
            '0852',
            '0853',
            '0855',
            '0856',
            '0857',
            '0858',
            '0877',
            '0878',
            '0881',
            '0882',
            '0883',
            '0884',
            '0885',
            '0886',
            '0887',
            '0888',
            '0889',
            '0895',
            '0896',
            '0897',
            '0898',
            '0899',
            '0828',
            '0831',
            '0832',
            '0833',
            '0838'
        ];

        $prefix = $this->faker->randomElement($prefixes);
        $suffix = $this->faker->numerify('#######');

        return $prefix . $suffix;
    }
}
