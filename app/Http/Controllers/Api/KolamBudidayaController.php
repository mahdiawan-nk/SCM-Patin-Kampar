<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\kolamBudidaya\KolamBudidayaService;
use App\Http\Requests\KolamBudidayaRequest;
use App\Http\Resources\KolamBudidayaResource;
use App\Models\KolamBudidaya;

class KolamBudidayaController extends Controller
{
    protected $KolamBudidayaService;

    public function __construct(KolamBudidayaService $KolamBudidayaService)
    {
        $this->KolamBudidayaService = $KolamBudidayaService;
    }

    public function index()
    {
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Data KolamBudidaya',
                'data' => new KolamBudidayaResource($this->KolamBudidayaService->indexKolamBudidaya())
            ],
            200
        );
    }

    public function store(KolamBudidayaRequest $request)
    {
        try {
            $this->KolamBudidayaService->storeKolamBudidaya($request);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamBudidaya',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamBudidaya',
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
                'message' => 'Data KolamBudidaya',
                'data' => new KolamBudidayaResource($this->KolamBudidayaService->showKolamBudidaya($id))
            ]
        );
    }

    public function update(Request $request, string $id)
    {
        try {
            $this->KolamBudidayaService->updateKolamBudidaya($request, $id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamBudidaya',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamBudidaya',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->KolamBudidayaService->destroyKolamBudidaya($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamBudidaya',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamBudidaya',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function trashed(string $id)
    {
        try {
            $this->KolamBudidayaService->trashKolamBudidaya($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamBudidaya trashed',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Failed KolamBudidaya trashed',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function restore(string $id)
    {
        try {
            $this->KolamBudidayaService->restoreKolamBudidaya($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamBudidaya',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamBudidaya',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function replicate(Request $request, string $id)
    {
        try {
            $this->KolamBudidayaService->replicate($id, $request->jml);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamBudidaya',
                    'data' => new KolamBudidayaResource($this->KolamBudidayaService->replicate($id, $request->jml))
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamBudidaya',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function createFaker(Request $request)
    {
        try {
            $this->KolamBudidayaService->createFaker($request->jml ?? 1, $request->pembudidaya_id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data Faker KolamBudidaya Berhasil Dibuat',
                    'data' => $request->all()
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamBudidaya',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }
}
