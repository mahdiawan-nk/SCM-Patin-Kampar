<?php

namespace App\Service\kolamSiklus;

use App\Models\KolamSiklus;
use App\Service\kolamSiklus\KolamSiklusIntefaces;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class KolamSiklusService implements IKolamSiklusInterfaces
{
    protected $model;

    public function __construct(KolamSiklus $model)
    {
        $this->model = $model;
    }

    public function indexKolamSiklus()
    {
        return $this->model->get();
    }

    public function storeKolamSiklus($request)
    {
        $data = $request->all();
        $store = $this->model->create($data);

        return $store;
    }

    public function showKolamSiklus($id)
    {
        return $this->model->find($id);
    }

    public function updateKolamSiklus($request, $id)
    {
        return $this->model->find($id)->update($request->all());
    }

    public function trashKolamSiklus($id)
    {
        return $this->model->find($id)->delete();
    }

    public function restoreKolamSiklus($id)
    {
        return $this->model->withTrashed()->find($id)->restore();
    }

    public function destroyKolamSiklus($id)
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
            $kolamSiklus = $this->model->create([]);

            

            $createdRecords[] = $kolamSiklus;
        }

        return $createdRecords;
    }
}
