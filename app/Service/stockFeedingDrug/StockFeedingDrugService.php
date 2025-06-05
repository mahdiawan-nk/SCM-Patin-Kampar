<?php

namespace App\Service\stockFeedingDrug;

use App\Models\StockFeedingDrug;
use App\Service\stockFeedingDrug\StockFeedingDrugIntefaces;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class StockFeedingDrugService implements IStockFeedingDrugInterfaces
{
    protected $model;

    public function __construct(StockFeedingDrug $model)
    {
        $this->model = $model;
    }

    public function indexStockFeedingDrug()
    {
        return $this->model->get();
    }

    public function storeStockFeedingDrug($request)
    {
        $data = $request->all();
        $store = $this->model->create($data);

        return $store;
    }

    public function showStockFeedingDrug($id)
    {
        return $this->model->find($id);
    }

    public function updateStockFeedingDrug($request, $id)
    {
        return $this->model->find($id)->update($request->all());
    }

    public function trashStockFeedingDrug($id)
    {
        return $this->model->find($id)->delete();
    }

    public function restoreStockFeedingDrug($id)
    {
        return $this->model->withTrashed()->find($id)->restore();
    }

    public function destroyStockFeedingDrug($id)
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
            $stockFeedingDrug = $this->model->create([]);

            

            $createdRecords[] = $stockFeedingDrug;
        }

        return $createdRecords;
    }
}
