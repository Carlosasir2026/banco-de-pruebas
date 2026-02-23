<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreAlmacenCarneRequest;
use App\Http\Requests\Api\V1\UpdateAlmacenCarneRequest;
use App\Models\Legacy\AlmacenCarne;
use Illuminate\Http\Request;

class AlmacenCarneController extends Controller
{
    public function index(Request $request)
    {
        $animal = trim((string) $request->query('animal', ''));
        $q = trim((string) $request->query('q', ''));

        $query = AlmacenCarne::query();

        if ($animal !== '') $query->where('animal', $animal);
        if ($q !== '') $query->where('pieza', 'ilike', "%{$q}%");

        $rows = $query->orderByDesc('ing_id')->limit(200)->get();

        return response()->json(['status' => 'success', 'items' => $rows]);
    }

    public function show(int $id)
    {
        $row = AlmacenCarne::findOrFail($id);
        return response()->json(['status' => 'success', 'item' => $row]);
    }

    public function store(StoreAlmacenCarneRequest $request)
    {
        $row = AlmacenCarne::create($request->validated());
        return response()->json(['status' => 'success', 'item' => $row], 201);
    }

    public function update(UpdateAlmacenCarneRequest $request, int $id)
    {
        $row = AlmacenCarne::findOrFail($id);
        $row->fill($request->validated());
        $row->save();
        return response()->json(['status' => 'success', 'item' => $row]);
    }

    public function destroy(int $id)
    {
        $row = AlmacenCarne::findOrFail($id);
        $row->delete();
        return response()->json(['status' => 'success', 'deleted' => true]);
    }

    public function enums()
    {
        $values = app(\App\Services\EnumService::class)->values('animal_carne_tipo');

        return response()->json([
            'status' => 'success',
            'animal_carne_tipo' => $values,
        ]);
    }
}