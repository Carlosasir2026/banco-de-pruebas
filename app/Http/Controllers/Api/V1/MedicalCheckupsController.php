<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Legacy\MedicalCheckup;
use Illuminate\Http\Request;

class MedicalCheckupsController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'id_animal' => ['required', 'integer'],
        ]);

        $rows = MedicalCheckup::query()
            ->where('id_animal', $request->integer('id_animal'))
            ->orderByDesc('fecha_consulta')
            ->orderByDesc('id_checkup')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $rows,
        ]);
    }

    public function latest(Request $request)
    {
        $request->validate([
            'id_animal' => ['required', 'integer'],
        ]);

        $row = MedicalCheckup::query()
            ->where('id_animal', $request->integer('id_animal'))
            ->orderByDesc('fecha_consulta')
            ->orderByDesc('id_checkup')
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => $row,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_animal' => ['required', 'integer'],
            'fecha_consulta' => ['required', 'date'], 
            'tipo_consulta' => ['nullable', 'string', 'max:50'],
            'motivo' => ['required', 'string'],
            'estado_general' => ['nullable', 'string', 'max:50'],
            'tratamiento' => ['nullable', 'string'],
            'medicacion' => ['nullable', 'string'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $row = MedicalCheckup::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $row,
        ], 201);
    }
}