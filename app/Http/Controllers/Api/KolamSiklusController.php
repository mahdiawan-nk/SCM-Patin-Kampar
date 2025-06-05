<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\Pembudiaya\KolamSiklusService;
use App\Http\Requests\KolamSiklusRequest;
use App\Http\Resources\KolamSiklusResource;
use App\Models\KolamSiklus;

class KolamSiklusController extends Controller
{
    protected $KolamSiklusService;

    public function __construct(KolamSiklusService $KolamSiklusService)
    {
        $this->KolamSiklusService = $KolamSiklusService;
    }

    public function index()
    {
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Data KolamSiklus',
                'data' => new KolamSiklusResource($this->KolamSiklusService->indexKolamSiklus())
            ],
            200
        );
    }

    public function store(KolamSiklusRequest $request)
    {
        try {
            $this->KolamSiklusService->storeKolamSiklus($request);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamSiklus',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamSiklus',
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
                'message' => 'Data KolamSiklus',
                'data' => new KolamSiklusResource($this->KolamSiklusService->showKolamSiklus($id))
            ]
        );
    }

    public function update(Request $request, string $id)
    {
        try {
            $this->KolamSiklusService->updateKolamSiklus($request, $id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamSiklus',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamSiklus',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->KolamSiklusService->destroyKolamSiklus($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamSiklus',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamSiklus',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function trash(string $id)
    {
        try {
            $this->KolamSiklusService->trashKolamSiklus($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamSiklus',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamSiklus',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function restore(string $id)
    {
        try {
            $this->KolamSiklusService->restoreKolamSiklus($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamSiklus',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamSiklus',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function replicate(Request $request, string $id)
    {
        try {
            $this->KolamSiklusService->replicate($id, $request->jml);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamSiklus',
                    'data' => new KolamSiklusResource($this->KolamSiklusService->replicate($id, $request->jml))
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamSiklus',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function createFaker(Request $request)
    {
        try {
            $this->KolamSiklusService->createFaker($request->jml ?? 1);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data Faker KolamSiklus Berhasil Dibuat',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamSiklus',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }
}
