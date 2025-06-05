<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\Pembudiaya\PemudidayaService;
use App\Http\Requests\PembudidayaRequest;
use App\Http\Resources\PembudidayaResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PembudidayaController extends Controller
{

    protected $pembudidayaService;

    public function __construct(PemudidayaService $pembudidayaService)
    {
        $this->pembudidayaService = $pembudidayaService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $page = max((int) $request->page, 1);
        $limit = (int) $request->per_page ?: 10;
        $offset = ($page - 1) * $limit;

        $baseQuery = $this->pembudidayaService->indexPembudidaya()
            ->when($search, function ($query) use ($search) {
                return $query->where('nama_lengkap', 'like', '%' . $search . '%');
            });

        $total = $baseQuery->count();

        $dataItem = $baseQuery
            ->paginate($limit);

        $no = $offset + 1; // nomor baris dimulai dari offset + 1
        foreach ($dataItem as $item) {
            $item->no = $no++;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data Pembudidaya',
            'data' => $dataItem,
            'total' => $total,
        ], 200);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(PembudidayaRequest $request)
    {
        try {
            $result = $this->pembudidayaService->storePembudidaya($request);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Succes Create Data Pembudidaya',
                    'data' => $result
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Failed Create Data Pembudidaya',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Data Pembudidaya',
                'data' => new PembudidayaResource($this->pembudidayaService->showPembudidaya($id))
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PembudidayaRequest $request, string $id)
    {
        try {
            $this->pembudidayaService->updatePembudidaya($request, $id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Update Data Pembudidaya',
                    'data' => []
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data Pembudidaya gagal di update',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->pembudidayaService->destroyPembudidaya($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data Pembudidaya deleted successfully',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data Pembudidaya failed deleted',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function trash(string $id)
    {
        try {
            $this->pembudidayaService->trashPembudidaya($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data Pembudidaya move to trash',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data Pembudidaya failed move to trash',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function restore(string $id)
    {
        try {
            $this->pembudidayaService->restorePembudidaya($id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data Pembudidaya restore from trash',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data Pembudidaya failed restore from trash',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function replicate(Request $request, string $id)
    {
        try {
            $this->pembudidayaService->replicate($id, $request->jml);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data Pembudidaya replicate',
                    'data' => []
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data Pembudidaya failed replicate',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function createFaker(Request $request)
    {
        try {
            $this->pembudidayaService->createFaker($request->jml ?? 1);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data Faker Pembudidaya Berhasil Dibuat',
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data Pembudidaya failed create faker',
                    'data' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function columnInput()
    {
        $database = DB::getDatabaseName();

        $columns = DB::table('information_schema.columns')
            ->select('COLUMN_NAME', 'DATA_TYPE', 'COLUMN_TYPE')
            ->where('table_schema', $database)
            ->where('table_name', 'pembudidayas')
            ->get();

        $fields = $columns->map(function ($col) {
            $field = [
                'name' => $col->COLUMN_NAME,
                'label' => Str::headline($col->COLUMN_NAME),
                'type' => $col->DATA_TYPE,
            ];

            if ($col->DATA_TYPE === 'enum') {
                preg_match('/enum\((.*)\)/', $col->DATA_TYPE, $matches);
                if (isset($matches[1])) {
                    $field['options'] = collect(explode(',', $matches[1]))
                        ->map(fn($val) => trim($val, "'"))
                        ->values();
                }
            }

            if ($col->DATA_TYPE === 'json') {
                $field['type'] = 'json';
            }

            return $field;
        })->values();

        return response()->json($fields);
    }
}
