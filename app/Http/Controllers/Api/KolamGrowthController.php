<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\KolamGrowth\KolamGrowthService;
use App\Http\Requests\KolamGrowthRequest;
use App\Http\Resources\KolamGrowthResource;
use App\Models\KolamGrowth;

class KolamGrowthController extends Controller
{
    protected $KolamGrowthService;

    public function __construct(KolamGrowthService $KolamGrowthService)
    {
        $this->KolamGrowthService = $KolamGrowthService;
    }

    public function index()
    {
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Data KolamGrowth',
                'data' => new KolamGrowthResource($this->KolamGrowthService->indexKolamGrowth())
            ],
            200
        );
    }

    public function store(KolamGrowthRequest $request)
    {
        try {
            $this->KolamGrowthService->storeKolamGrowth($request);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamGrowth',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamGrowth',
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
                'message' => 'Data KolamGrowth',
                'data' => new KolamGrowthResource($this->KolamGrowthService->showKolamGrowth($id))
            ]
        );
    }

    public function update(Request $request, string $id)
    {
        try {
            $this->KolamGrowthService->updateKolamGrowth($request, $id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamGrowth',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamGrowth',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->KolamGrowthService->destroyKolamGrowth($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamGrowth',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamGrowth',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function trash(string $id)
    {
        try {
            $this->KolamGrowthService->trashKolamGrowth($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamGrowth',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamGrowth',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function restore(string $id)
    {
        try {
            $this->KolamGrowthService->restoreKolamGrowth($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamGrowth',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamGrowth',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function replicate(Request $request, string $id)
    {
        try {
            $this->KolamGrowthService->replicate($id, $request->jml);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamGrowth',
                    'data' => new KolamGrowthResource($this->KolamGrowthService->replicate($id, $request->jml))
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamGrowth',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function createFaker(Request $request)
    {
        try {
            $this->KolamGrowthService->createFaker($request->jml ?? 1);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data Faker KolamGrowth Berhasil Dibuat',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamGrowth',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }
}
