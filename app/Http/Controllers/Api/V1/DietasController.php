<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Legacy\Dieta;
use Illuminate\Http\Request;

class DietasController extends Controller
{
    // GET /api/v1/dietas?id_animal=31
    public function index(Request $request)
    {
        $request->validate([
            'id_animal' => ['required','integer'],
        ]);

        $rows = Dieta::query()
            ->where('id_animal', $request->integer('id_animal'))
            ->orderByDesc('id_dieta')
            ->get();

        return response()->json([
            'status' => 'success',
            'dietas' => $rows,
        ]);
    }

    // GET /api/v1/dietas/{id}
    public function show(int $id)
    {
        $row = Dieta::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'dieta' => $row,
        ]);
    }

    // POST /api/v1/dietas
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_animal' => ['required','integer'],
            'nombre' => ['required','string','max:255'],
            // array de strings (opcional)
            'ingredientes' => ['nullable','array'],
            'ingredientes.*' => ['string','max:255'],
        ]);

        $row = Dieta::create($data);

        return response()->json([
            'status' => 'success',
            'dieta' => $row,
        ], 201);
    }

    // PUT /api/v1/dietas/{id}
    public function update(Request $request, int $id)
    {
        $row = Dieta::findOrFail($id);

        $data = $request->validate([
            'nombre' => ['sometimes','string','max:255'],
            'ingredientes' => ['sometimes','nullable','array'],
            'ingredientes.*' => ['string','max:255'],
        ]);

        $row->fill($data);
        $row->save();

        return response()->json([
            'status' => 'success',
            'dieta' => $row,
        ]);
    }

    // DELETE /api/v1/dietas/{id}
    public function destroy(int $id)
    {
        $row = Dieta::findOrFail($id);
        $row->delete();

        return response()->json([
            'status' => 'success',
            'deleted' => true,
        ]);
    }
}