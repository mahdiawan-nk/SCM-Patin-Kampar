<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\Pembudiaya\StockFeedingDrugService;
use App\Http\Requests\StockFeedingDrugRequest;
use App\Http\Resources\StockFeedingDrugResource;
use App\Models\StockFeedingDrug;

class StockFeedingDrugController extends Controller
{
    protected $StockFeedingDrugService;

    public function __construct(StockFeedingDrugService $StockFeedingDrugService)
    {
        $this->StockFeedingDrugService = $StockFeedingDrugService;
    }

    public function index()
    {
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Data StockFeedingDrug',
                'data' => new StockFeedingDrugResource($this->StockFeedingDrugService->indexStockFeedingDrug())
            ],
            200
        );
    }

    public function store(StockFeedingDrugRequest $request)
    {
        try {
            $this->StockFeedingDrugService->storeStockFeedingDrug($request);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data StockFeedingDrug',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data StockFeedingDrug',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function show(string $id)
    {
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Data StockFeedingDrug',
                'data' => new StockFeedingDrugResource($this->StockFeedingDrugService->showStockFeedingDrug($id))
            ]
        );
    }

    public function update(Request $request, string $id)
    {
        try {
            $this->StockFeedingDrugService->updateStockFeedingDrug($request, $id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data StockFeedingDrug',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data StockFeedingDrug',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->StockFeedingDrugService->destroyStockFeedingDrug($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data StockFeedingDrug',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data StockFeedingDrug',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function trash(string $id)
    {
        try {
            $this->StockFeedingDrugService->trashStockFeedingDrug($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data StockFeedingDrug',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data StockFeedingDrug',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function restore(string $id)
    {
        try {
            $this->StockFeedingDrugService->restoreStockFeedingDrug($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data StockFeedingDrug',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data StockFeedingDrug',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function replicate(Request $request, string $id)
    {
        try {
            $this->StockFeedingDrugService->replicate($id, $request->jml);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data StockFeedingDrug',
                    'data' => new StockFeedingDrugResource($this->StockFeedingDrugService->replicate($id, $request->jml))
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data StockFeedingDrug',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function createFaker(Request $request)
    {
        try {
            $this->StockFeedingDrugService->createFaker($request->jml ?? 1);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data Faker StockFeedingDrug Berhasil Dibuat',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data StockFeedingDrug',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }
}
