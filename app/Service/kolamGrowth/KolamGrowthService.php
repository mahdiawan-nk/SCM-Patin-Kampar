<?php

namespace App\Service\kolamGrowth;

use App\Models\KolamGrowth;
use App\Service\kolamGrowth\KolamGrowthInterfaces;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class KolamGrowthService implements KolamGrowthInterfaces
{
    protected $model;

    public function __construct(KolamGrowth $model)
    {
        $this->model = $model;
    }

    public function indexKolamGrowth()
    {
        return $this->model->get();
    }

    public function storeKolamGrowth($request)
    {
        $data = $request->all();
        $store = $this->model->create($data);

        return $store;
    }

    public function showKolamGrowth($id)
    {
        return $this->model->find($id);
    }

    public function updateKolamGrowth($request, $id)
    {
        return $this->model->find($id)->update($request->all());
    }

    public function trashKolamGrowth($id)
    {
        return $this->model->find($id)->delete();
    }

    public function restoreKolamGrowth($id)
    {
        return $this->model->withTrashed()->find($id)->restore();
    }

    public function destroyKolamGrowth($id)
    {
        return $this->model->withTrashed()->find($id)->forceDelete();
    }

    public function replicate($id, $jml = 1)
    {
        $original = $this->model->with('usaha')->findOrFail($id);
        $replicas = [];

        for ($i = 0; $i < $jml; $i++) {
            $new = $original->replicate();
            $new->uuid = Str::uuid();
            $new->save();
        }

        return $replicas;
    }


    public function createFaker($jml = 1)
    {
        $faker = Faker::create();
        $createdRecords = [];

        for ($i = 0; $i < $jml; $i++) {
            $kolamGrowth = $this->model->create([]);

            

            $createdRecords[] = $kolamGrowth;
        }

        return $createdRecords;
    }
}
