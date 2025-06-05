<?php

namespace App\Service\kolamMonitoring;

use App\Models\KolamMonitoring;
use App\Service\kolamMonitoring\KolamMonitoringInterfaces;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class KolamMonitoringService implements KolamMonitoringInterfaces
{
    protected $model;

    public function __construct(KolamMonitoring $model)
    {
        $this->model = $model;
    }

    public function indexKolamMonitoring()
    {
        return $this->model->get();
    }

    public function storeKolamMonitoring($request)
    {
        $data = $request->all();
        $store = $this->model->create($data);

        return $store;
    }

    public function showKolamMonitoring($id)
    {
        return $this->model->find($id);
    }

    public function updateKolamMonitoring($request, $id)
    {
        return $this->model->find($id)->update($request->all());
    }

    public function trashKolamMonitoring($id)
    {
        return $this->model->find($id)->delete();
    }

    public function restoreKolamMonitoring($id)
    {
        return $this->model->withTrashed()->find($id)->restore();
    }

    public function destroyKolamMonitoring($id)
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
            $kolamMonitoring = $this->model->create([]);

            

            $createdRecords[] = $kolamMonitoring;
        }

        return $createdRecords;
    }
}
