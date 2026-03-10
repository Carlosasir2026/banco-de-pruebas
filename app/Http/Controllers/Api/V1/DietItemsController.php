<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Legacy\DietItem;
use Illuminate\Http\Request;

class DietItemsController extends Controller
{
    // GET /api/v1/dietas/{id}/items
    public function indexByDieta(int $id_dieta)
    {
        $rows = DietItem::query()
            ->where('id_dieta', $id_dieta)
            ->orderBy('id_diet_item')
            ->get();

        return response()->json([
            'status' => 'success',
            'items' => $rows,
        ]);
    }

    // POST /api/v1/dietas/{id}/items
    public function storeForDieta(Request $request, int $id_dieta)
    {
        $data = $request->validate([
            'ingrediente' => ['required','string','max:255'],
            'gramos' => ['required','numeric','min:0'],
            'id_alimento' => ['nullable','integer'],
        ]);

        $row = DietItem::create([
            'id_dieta' => $id_dieta,
            ...$data,
        ]);

        return response()->json([
            'status' => 'success',
            'item' => $row,
        ], 201);
    }

    // PUT /api/v1/diet-items/{id}
    public function update(Request $request, int $id)
    {
        $row = DietItem::findOrFail($id);

        $data = $request->validate([
            'ingrediente' => ['sometimes','string','max:255'],
            'gramos' => ['sometimes','numeric','min:0'],
            'id_alimento' => ['sometimes','nullable','integer'],
        ]);

        $row->fill($data);
        $row->save();

        return response()->json([
            'status' => 'success',
            'item' => $row,
        ]);
    }

    // DELETE /api/v1/diet-items/{id}
    public function destroy(int $id)
    {
        $row = DietItem::findOrFail($id);
        $row->delete();

        return response()->json([
            'status' => 'success',
            'deleted' => true,
        ]);
    }
}