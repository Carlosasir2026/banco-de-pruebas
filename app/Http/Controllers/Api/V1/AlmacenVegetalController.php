<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreAlmacenVegetalRequest;
use App\Http\Requests\Api\V1\UpdateAlmacenVegetalRequest;
use App\Models\Legacy\AlmacenVegetal;
use Illuminate\Http\Request;

class AlmacenVegetalController extends Controller
{
    // GET /api/v1/almacen/vegetal?categoria=...&q=...
    public function index(Request $request)
    {
        $categoria = trim((string) $request->query('categoria', ''));
        $q = trim((string) $request->query('q', ''));

        $query = AlmacenVegetal::query();

        if ($categoria !== '') {
            $query->where('categoria', $categoria);
        }
        if ($q !== '') {
            $query->where('nombre', 'ilike', "%{$q}%");
        }

        $rows = $query->orderByDesc('ing_id')->limit(200)->get();

        return response()->json([
            'status' => 'success',
            'items' => $rows,
        ]);
    }

    // GET /api/v1/almacen/vegetal/{id}
    public function show(int $id)
    {
        $row = AlmacenVegetal::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'item' => $row,
        ]);
    }

    // POST /api/v1/almacen/vegetal
    public function store(StoreAlmacenVegetalRequest $request)
    {
        $row = AlmacenVegetal::create($request->validated());

        return response()->json([
            'status' => 'success',
            'item' => $row,
        ], 201);
    }

    // PUT /api/v1/almacen/vegetal/{id}
    public function update(UpdateAlmacenVegetalRequest $request, int $id)
    {
        $row = AlmacenVegetal::findOrFail($id);
        $row->fill($request->validated());
        $row->save();

        return response()->json([
            'status' => 'success',
            'item' => $row,
        ]);
    }

    // DELETE /api/v1/almacen/vegetal/{id}
    public function destroy(int $id)
    {
        $row = AlmacenVegetal::findOrFail($id);
        $row->delete();

        return response()->json([
            'status' => 'success',
            'deleted' => true,
        ]);
    }

    // GET /api/v1/almacen/vegetal/enums
    public function enums()
    {
        $values = app(\App\Services\EnumService::class)->values('categoria_vegetal_tipo');

        return response()->json([
            'status' => 'success',
            'categoria_vegetal_tipo' => $values,
        ]);
    }
}