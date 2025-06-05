<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\KolamFeeding\KolamFeedingService;
use App\Http\Requests\KolamFeedingRequest;
use App\Http\Resources\KolamFeedingResource;
use App\Models\KolamFeeding;

class KolamFeedingController extends Controller
{
    protected $KolamFeedingService;

    public function __construct(KolamFeedingService $KolamFeedingService)
    {
        $this->KolamFeedingService = $KolamFeedingService;
    }

    public function index()
    {
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Data KolamFeeding',
                'data' => new KolamFeedingResource($this->KolamFeedingService->indexKolamFeeding())
            ],
            200
        );
    }

    public function store(KolamFeedingRequest $request)
    {
        try {
            $this->KolamFeedingService->storeKolamFeeding($request);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamFeeding',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamFeeding',
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
                'message' => 'Data KolamFeeding',
                'data' => new KolamFeedingResource($this->KolamFeedingService->showKolamFeeding($id))
            ]
        );
    }

    public function update(Request $request, string $id)
    {
        try {
            $this->KolamFeedingService->updateKolamFeeding($request, $id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamFeeding',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamFeeding',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->KolamFeedingService->destroyKolamFeeding($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamFeeding',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamFeeding',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function trash(string $id)
    {
        try {
            $this->KolamFeedingService->trashKolamFeeding($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamFeeding',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamFeeding',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function restore(string $id)
    {
        try {
            $this->KolamFeedingService->restoreKolamFeeding($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamFeeding',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamFeeding',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function replicate(Request $request, string $id)
    {
        try {
            $this->KolamFeedingService->replicate($id, $request->jml);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamFeeding',
                    'data' => new KolamFeedingResource($this->KolamFeedingService->replicate($id, $request->jml))
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamFeeding',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function createFaker(Request $request)
    {
        try {
            $this->KolamFeedingService->createFaker($request->jml ?? 1);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data Faker KolamFeeding Berhasil Dibuat',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamFeeding',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }
}
