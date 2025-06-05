<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\Pembudiaya\KolamTreatmentService;
use App\Http\Requests\KolamTreatmentRequest;
use App\Http\Resources\KolamTreatmentResource;
use App\Models\KolamTreatment;

class KolamTreatmentController extends Controller
{
    protected $KolamTreatmentService;

    public function __construct(KolamTreatmentService $KolamTreatmentService)
    {
        $this->KolamTreatmentService = $KolamTreatmentService;
    }

    public function index()
    {
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Data KolamTreatment',
                'data' => new KolamTreatmentResource($this->KolamTreatmentService->indexKolamTreatment())
            ],
            200
        );
    }

    public function store(KolamTreatmentRequest $request)
    {
        try {
            $this->KolamTreatmentService->storeKolamTreatment($request);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamTreatment',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamTreatment',
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
                'message' => 'Data KolamTreatment',
                'data' => new KolamTreatmentResource($this->KolamTreatmentService->showKolamTreatment($id))
            ]
        );
    }

    public function update(Request $request, string $id)
    {
        try {
            $this->KolamTreatmentService->updateKolamTreatment($request, $id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamTreatment',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamTreatment',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->KolamTreatmentService->destroyKolamTreatment($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamTreatment',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamTreatment',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function trash(string $id)
    {
        try {
            $this->KolamTreatmentService->trashKolamTreatment($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamTreatment',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamTreatment',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function restore(string $id)
    {
        try {
            $this->KolamTreatmentService->restoreKolamTreatment($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamTreatment',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamTreatment',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function replicate(Request $request, string $id)
    {
        try {
            $this->KolamTreatmentService->replicate($id, $request->jml);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamTreatment',
                    'data' => new KolamTreatmentResource($this->KolamTreatmentService->replicate($id, $request->jml))
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamTreatment',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function createFaker(Request $request)
    {
        try {
            $this->KolamTreatmentService->createFaker($request->jml ?? 1);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data Faker KolamTreatment Berhasil Dibuat',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamTreatment',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }
}
