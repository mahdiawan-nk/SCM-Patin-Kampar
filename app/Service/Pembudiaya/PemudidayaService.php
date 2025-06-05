<?php

namespace App\Service\Pembudiaya;

use App\Models\Pembudidaya;
use App\Service\Pembudiaya\PemudidayaInterfaces;
use App\Models\PembudidayaUsaha;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class PemudidayaService implements PemudidayaInterfaces
{
    protected $model;
    /**
     * Create a new class instance.
     */
    public function __construct(Pembudidaya $model)
    {
        $this->model = $model;
    }

    public function indexPembudidaya()
    {
        return $this->model->with('usaha')->select(
            'id',
            'uuid',
            'nama_lengkap',
            'nik',
            'jenis_kelamin',
            'tanggal_lahir',
            'no_hp',
            'email',
            'alamat_lengkap',
            'tgl_bergabung',
            'status',
            'created_at',
            'updated_at',
            'deleted_at'
        );
    }

    public function storePembudidaya($request)
    {
        $data = $request->all();
        $data['kordinat'] = json_encode($data['kordinat']);
        $pembudidaya = $this->model->create($data);

        $pembudidaya->usaha()->create($data);

        return $pembudidaya;
    }
    public function showPembudidaya($id)
    {
        return $this->model->with('usaha')->find($id);
    }
    public function updatePembudidaya($request, $id)
    {
        $data = $request->all();
        $data['kordinat'] = json_encode($data['kordinat']);
        $pembudidaya = $this->model->find($id)->update($request->all());

        $dataUsaha = PembudidayaUsaha::where('pembudidaya_id', $id)->first();
        $dataUsaha->update($data);

        return $pembudidaya;
    }
    public function trashPembudidaya($id)
    {
        return $this->model->find($id)->delete();
    }
    public function restorePembudidaya($id)
    {
        return $this->model->withTrashed()->find($id)->restore();
    }
    public function destroyPembudidaya($id)
    {
        return $this->model->withTrashed()->find($id)->forceDelete();
    }


    public function replicate($id, $jml = 1)
    {
        $original = $this->model->with('usaha')->findOrFail($id);
        $replicas = [];

        for ($i = 0; $i < $jml; $i++) {
            // Clone Pembudidaya
            $new = $original->replicate();

            // UUID & NIK baru
            $new->uuid = Str::uuid();
            $new->nik  = $this->generateUniqueNik();

            $new->save();

            // Clone Usaha yang terkait (relasi hasOne)
            if ($original->usaha) {
                $newUsaha = $original->usaha->replicate();
                $newUsaha->uuid = Str::uuid();
                $newUsaha->pembudidaya_id = $new->id;
                $newUsaha->save();
            }

            $replicas[] = $new;
        }

        return $replicas;
    }

    protected function generateUniqueNik(): string
    {
        do {
            $nik = (string) random_int(1000000000000000, 9999999999999999);
        } while ($this->model->where('nik', $nik)->exists());

        return $nik;
    }

    public function createFaker($jml = 1)
    {
        $faker = Faker::create();
        $createdRecords = [];

        for ($i = 0; $i < $jml; $i++) {
            // Membuat Pembudidaya baru menggunakan Faker
            $pembudidaya = $this->model->create([
                'uuid'             => Str::uuid(),
                'nik'              => $this->generateUniqueNik(),
                'nama_lengkap'     => $faker->name,
                'jenis_kelamin'    => $faker->randomElement(['L', 'P']),
                'tanggal_lahir'    => $faker->date(),
                'email'            => $faker->email,
                'no_hp'            => $faker->phoneNumber,
                'alamat_lengkap'   => $faker->address,
                'status'           => $faker->randomElement(['aktif', 'tidak aktif']),
                'tgl_bergabung'    => $faker->date(),
            ]);

            // Membuat Usaha terkait dengan Pembudidaya
            $usaha = $pembudidaya->usaha()->create([
                'uuid'                     => Str::uuid(),
                'nama_usaha'               => $faker->company,
                'jenis_usaha'              => $faker->word,
                'luas_lahan'               => $faker->randomNumber(3),
                'jumlah_kolam'             => $faker->randomDigitNotNull,
                'sistem_budidaya'          => $faker->word,
                'jenis_izin_usaha'         => $faker->word,
                'tahun_mulai_usaha'        => $faker->year,
                'status_kepemilikan_usaha' => $faker->randomElement(['milik sendiri', 'sewa', 'kelompok']),
                'nama_kelompok'            => $faker->companySuffix,
                'jabatan_di_kelompok'      => $faker->jobTitle,
                'provinsi'                 => $faker->state,
                'kabupaten'                => $faker->city,
                'kecamatan'                => $faker->city,
                'desa'                     => $faker->city,
                'alamat_usaha'             => $faker->address,
                'kordinat'                 => json_encode([
                    'lat' => $faker->latitude,
                    'lng' => $faker->longitude,
                ]),
                'no_izin_usaha'            => $faker->word,
                'ktp_scan'                 => $faker->imageUrl(),
                'foto_lokasi'              => $faker->imageUrl(),
                'surat_izin'               => $faker->imageUrl(),
            ]);

            // Menyimpan hasil replikasi
            $createdRecords[] = $pembudidaya;
        }

        return $createdRecords;
    }
}
