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

    public function batch(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || count($ids) === 0) {
            return response()->json([
                'status' => 'success',
                'alimentos' => (object)[],
            ]);
        }

        // Sanitiza: solo ints, únicos, y limita para seguridad
        $ids = array_values(array_unique(array_map('intval', $ids)));
        $ids = array_slice($ids, 0, 500);

        $rows = Alimento::query()
            ->whereIn('id_alimento', $ids)
            ->get();

        // Devuelve como mapa por id para lookup O(1) en Flutter
        $map = [];
        foreach ($rows as $row) {
            $map[(string)$row->id_alimento] = $row;
        }

        return response()->json([
            'status' => 'success',
            'alimentos' => $map,
        ]);
    }
}