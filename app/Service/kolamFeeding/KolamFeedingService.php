<?php

namespace App\Service\kolamFeeding;

use App\Models\KolamFeeding;
use App\Service\kolamFeeding\KolamFeedingInterfaces;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class KolamFeedingService implements KolamFeedingInterfaces
{
    protected $model;

    public function __construct(KolamFeeding $model)
    {
        $this->model = $model;
    }

    public function indexKolamFeeding()
    {
        return $this->model->get();
    }

    public function storeKolamFeeding($request)
    {
        $data = $request->all();
        $store = $this->model->create($data);

        return $store;
    }

    public function showKolamFeeding($id)
    {
        return $this->model->find($id);
    }

    public function updateKolamFeeding($request, $id)
    {
        return $this->model->find($id)->update($request->all());
    }

    public function trashKolamFeeding($id)
    {
        return $this->model->find($id)->delete();
    }

    public function restoreKolamFeeding($id)
    {
        return $this->model->withTrashed()->find($id)->restore();
    }

    public function destroyKolamFeeding($id)
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
            $kolamFeeding = $this->model->create([]);

            

            $createdRecords[] = $kolamFeeding;
        }

        return $createdRecords;
    }
}
