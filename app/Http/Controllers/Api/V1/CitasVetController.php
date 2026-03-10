<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Legacy\CitaVet;
use Illuminate\Http\Request;

class CitasVetController extends Controller
{
    // GET /api/v1/citas-vet?user_id=34 (opcional) o id_animal=31
    // Para tu caso: vamos a usar id_animal
    public function index(Request $request)
    {
        $request->validate([
            'id_animal' => ['required', 'integer'],
        ]);

        $rows = CitaVet::query()
            ->where('id_animal', $request->integer('id_animal'))
            ->orderByDesc('fecha')
            ->orderByDesc('hora')
            ->orderByDesc('id_cita')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $rows,
        ]);
    }

    // GET /api/v1/citas-vet/next?id_animal=31
    public function next(Request $request)
    {
        $request->validate([
            'id_animal' => ['required', 'integer'],
        ]);

        $row = CitaVet::query()
            ->where('id_animal', $request->integer('id_animal'))
            ->where(function ($q) {
                $q->where('fecha', '>', now()->toDateString())
                  ->orWhere(function ($q2) {
                      $q2->where('fecha', now()->toDateString())
                         ->where('hora', '>=', now()->format('H:i:s'));
                  });
            })
            ->orderBy('fecha')
            ->orderBy('hora')
            ->orderBy('id_cita')
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => $row, // puede ser null
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_animal'   => ['required', 'integer'],
            'fecha'       => ['required', 'date'],                 // YYYY-MM-DD
            'hora'        => ['required', 'regex:/^\d{2}:\d{2}$/'],// HH:MM
            'clinica_vet' => ['required', 'string', 'max:150'],    // NOT NULL en BD
            'telefono'    => ['nullable', 'string', 'max:20'],
            'notas'       => ['nullable', 'string'],
        ]);

        $validated['hora'] = $validated['hora'] . ':00';
        $validated['created_at'] = now()->format('Y-m-d H:i:s');

        $row = \App\Models\Legacy\CitaVet::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $row,
        ], 201);
    }
}