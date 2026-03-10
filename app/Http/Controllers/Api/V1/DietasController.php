<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Legacy\Dieta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DietasController extends Controller
{
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

    public function show(int $id)
    {
        $row = Dieta::query()->where('id_dieta', $id)->firstOrFail();

        $items = DB::table('diet_items')
            ->where('id_dieta', $id)
            ->orderBy('id_diet_item')
            ->get([
                'id_diet_item',
                'id_dieta',
                'ingrediente',
                'gramos',
                'id_alimento',
            ]);

        return response()->json([
            'status' => 'success',
            'dieta'  => $row,
            'items'  => $items,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_animal' => ['required','integer'],
            'nombre' => ['required','string','max:255'],
            'ingredientes' => ['nullable','array'],
            'ingredientes.*' => ['string','max:255'],
        ]);

        $row = Dieta::create($data);

        return response()->json([
            'status' => 'success',
            'dieta' => $row,
        ], 201);
    }

    public function update(Request $request, int $id)
    {
        $row = Dieta::query()->where('id_dieta', $id)->firstOrFail();

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

    public function destroy(int $id)
    {
        $row = Dieta::query()->where('id_dieta', $id)->firstOrFail();

        DB::table('diet_items')->where('id_dieta', $id)->delete();

        $row->delete();

        return response()->json([
            'status' => 'success',
            'deleted' => true,
        ]);
    }
}