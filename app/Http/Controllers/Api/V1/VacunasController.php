<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Legacy\VacunaAnimal;
use Illuminate\Http\Request;

class VacunasController extends Controller
{
    // GET /api/v1/vacunas?id_animal=31
    public function index(Request $request)
    {
        $request->validate([
            'id_animal' => ['required', 'integer'],
        ]);

        $idAnimal = (int) $request->input('id_animal');

        $rows = VacunaAnimal::query()
            ->where('id_animal', $idAnimal)
            ->orderByRaw('fecha IS NULL') // primero las que tienen fecha
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $rows,
        ]);
    }

    // POST /api/v1/vacunas
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_animal' => ['required', 'integer'],
            'vacuna' => ['required', 'string', 'max:120'],
            'fecha' => ['nullable', 'date'],
            'clinica_vet' => ['nullable', 'string', 'max:160'],
            'lote' => ['nullable', 'string', 'max:80'],
            'notas' => ['nullable', 'string'],
        ]);

        $row = VacunaAnimal::create($data);

        return response()->json([
            'ok' => true,
            'data' => $row,
        ], 201);
    }
}