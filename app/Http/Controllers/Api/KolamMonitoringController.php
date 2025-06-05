<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\KolamMonitoring\KolamMonitoringService;
use App\Http\Requests\KolamMonitoringRequest;
use App\Http\Resources\KolamMonitoringResource;
use App\Models\KolamMonitoring;

class KolamMonitoringController extends Controller
{
    protected $KolamMonitoringService;

    public function __construct(KolamMonitoringService $KolamMonitoringService)
    {
        $this->KolamMonitoringService = $KolamMonitoringService;
    }

    public function index()
    {
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Data KolamMonitoring',
                'data' => new KolamMonitoringResource($this->KolamMonitoringService->indexKolamMonitoring())
            ],
            200
        );
    }

    public function store(KolamMonitoringRequest $request)
    {
        try {
            $this->KolamMonitoringService->storeKolamMonitoring($request);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamMonitoring',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamMonitoring',
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
                'message' => 'Data KolamMonitoring',
                'data' => new KolamMonitoringResource($this->KolamMonitoringService->showKolamMonitoring($id))
            ]
        );
    }

    public function update(Request $request, string $id)
    {
        try {
            $this->KolamMonitoringService->updateKolamMonitoring($request, $id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamMonitoring',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamMonitoring',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->KolamMonitoringService->destroyKolamMonitoring($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamMonitoring',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamMonitoring',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function trash(string $id)
    {
        try {
            $this->KolamMonitoringService->trashKolamMonitoring($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamMonitoring',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamMonitoring',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function restore(string $id)
    {
        try {
            $this->KolamMonitoringService->restoreKolamMonitoring($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamMonitoring',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamMonitoring',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function replicate(Request $request, string $id)
    {
        try {
            $this->KolamMonitoringService->replicate($id, $request->jml);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data KolamMonitoring',
                    'data' => new KolamMonitoringResource($this->KolamMonitoringService->replicate($id, $request->jml))
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamMonitoring',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function createFaker(Request $request)
    {
        try {
            $this->KolamMonitoringService->createFaker($request->jml ?? 1);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data Faker KolamMonitoring Berhasil Dibuat',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data KolamMonitoring',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }
}
