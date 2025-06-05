<?php

namespace App\Service\kolamTreatment;

use App\Models\KolamTreatment;
use App\Service\kolamTreatment\KolamTreatmentIntefaces;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class KolamTreatmentService implements IKolamTreatmentInterfaces
{
    protected $model;

    public function __construct(KolamTreatment $model)
    {
        $this->model = $model;
    }

    public function indexKolamTreatment()
    {
        return $this->model->get();
    }

    public function storeKolamTreatment($request)
    {
        $data = $request->all();
        $store = $this->model->create($data);

        return $store;
    }

    public function showKolamTreatment($id)
    {
        return $this->model->find($id);
    }

    public function updateKolamTreatment($request, $id)
    {
        return $this->model->find($id)->update($request->all());
    }

    public function trashKolamTreatment($id)
    {
        return $this->model->find($id)->delete();
    }

    public function restoreKolamTreatment($id)
    {
        return $this->model->withTrashed()->find($id)->restore();
    }

    public function destroyKolamTreatment($id)
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
            $kolamTreatment = $this->model->create([]);

            

            $createdRecords[] = $kolamTreatment;
        }

        return $createdRecords;
    }
}
