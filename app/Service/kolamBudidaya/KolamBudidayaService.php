<?php

namespace App\Service\kolamBudidaya;

use App\Models\KolamBudidaya;
use App\Service\kolamBudidaya\KolamBudidayaInterfaces;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class KolamBudidayaService implements KolamBudidayaInterfaces
{
    protected $model;

    public function __construct(KolamBudidaya $model)
    {
        $this->model = $model;
    }

    public function indexKolamBudidaya()
    {
        return $this->model->get();
    }

    public function storeKolamBudidaya($request)
    {
        $data = $request->all();
        $store = $this->model->create($data);

        return $store;
    }

    public function showKolamBudidaya($id)
    {
        return $this->model->find($id);
    }

    public function updateKolamBudidaya($request, $id)
    {
        return $this->model->find($id)->update($request->all());
    }

    public function trashKolamBudidaya($id)
    {
        return $this->model->find($id)->delete();
    }

    public function restoreKolamBudidaya($id)
    {
        return $this->model->withTrashed()->find($id)->restore();
    }

    public function destroyKolamBudidaya($id)
    {
        return $this->model->withTrashed()->find($id)->forceDelete();
    }

    public function replicate($id, $jml = 1)
    {
        $original = $this->model->findOrFail($id);
        $replicas = [];

        for ($i = 0; $i < $jml; $i++) {
            $new = $original->replicate();
            $new->uuid = Str::uuid();
            $new->save();
        }

        return $replicas;
    }


    public function createFaker($jml = 1, $param)
    {
        $faker = Faker::create();
        $createdRecords = [];

        for ($i = 0; $i < $jml; $i++) {
            $kolamBudidaya = $this->model->create([
                'pembudidaya_id' => $param,
                'nama_kolam' => $faker->name,
                'lokasi_kolam' => $faker->address,
                'panjang' => $faker->randomNumber(),
                'lebar' => $faker->randomNumber(),
                'kedalaman' => $faker->randomNumber(),
                'volume_air' => $faker->randomNumber(),
                'kapasitas' => $faker->randomNumber(),
                'jenis_kolam' => $faker->randomElement(['tanah', 'terpal', 'beton', 'keramba']),
                'status' => $faker->randomElement(['aktif', 'maintenance', 'tidak aktif']),
            ]);

            $createdRecords[] = $kolamBudidaya;
        }

        return $createdRecords;
    }
}
