<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Legacy\Alimento;
use Illuminate\Http\Request;

class AlimentosController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $tipo = trim((string) $request->query('tipo', ''));

        $query = Alimento::query();

        if ($q !== '') {
            $query->where('nombre', 'ilike', "%{$q}%");
        }
        if ($tipo !== '') {
            $query->where('tipo_de_alimento', 'ilike', "%{$tipo}%");
        }

        $rows = $query->orderBy('nombre')->limit(200)->get();

        return response()->json([
            'status' => 'success',
            'alimentos' => $rows,
        ]);
    }

    // GET /api/v1/alimentos/{id}
    public function show(int $id)
    {
        $row = Alimento::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'alimento' => $row,
        ]);
    }
}