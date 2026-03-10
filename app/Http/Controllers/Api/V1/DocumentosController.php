<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Legacy\DocumentoAnimal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentosController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'id_animal' => ['required', 'integer'],
        ]);

        $idAnimal = (int) $request->input('id_animal');

        $rows = DocumentoAnimal::query()
            ->where('id_animal', $idAnimal)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($d) {
                $path = (string) ($d->path ?? '');

                // ✅ PK real (id_documento). getKey() respeta $primaryKey del modelo.
                $pk = $d->getKey();

                return [
                    // ✅ mantengo "id" pero ahora es el PK real para que el front no se rompa
                    'id' => $pk,
                    // ✅ opcional: también lo mando explícito por claridad
                    'id_documento' => $pk,

                    'id_animal' => $d->id_animal,
                    'nombre_original' => $d->nombre_original,
                    'path' => $path,
                    'notas' => $d->notas,
                    'created_at' => optional($d->created_at)->toISOString(),
                    'updated_at' => optional($d->updated_at)->toISOString(),
                    'url' => $path ? asset('storage/' . ltrim($path, '/')) : null,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $rows,
        ]);
    }

    // POST /api/v1/documentos  (multipart/form-data)
    // fields: id_animal (int), notas (string opcional), file (archivo)
    public function store(Request $request)
    {
        $request->validate([
            'id_animal' => ['required', 'integer'],
            'file' => ['required', 'file', 'max:20480'], // 20MB
            'notas' => ['nullable', 'string', 'max:2000'],
        ]);

        $idAnimal = (int) $request->input('id_animal');
        $file = $request->file('file');

        // Validar extensiones permitidas (pdf/doc/docx/png/jpg/jpeg)
        $ext = strtolower($file->getClientOriginalExtension() ?: '');
        $allowed = ['pdf', 'doc', 'docx', 'png', 'jpg', 'jpeg'];

        if (!in_array($ext, $allowed, true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tipo de archivo no permitido. Usa: pdf, doc, docx, png, jpg, jpeg.',
            ], 422);
        }

        $originalName = $file->getClientOriginalName() ?: ('documento.' . $ext);

        // Nombre seguro (evita espacios raros / caracteres raros)
        $base = pathinfo($originalName, PATHINFO_FILENAME);
        $safeBase = Str::slug($base);
        if ($safeBase === '') $safeBase = 'documento';

        $filename = $safeBase . '-' . now()->format('YmdHis') . '.' . $ext;

        // Guarda en storage/app/public/documentos_animales/{id_animal}/...
        $dir = 'documentos_animales/' . $idAnimal;
        $path = $file->storeAs($dir, $filename, 'public'); // => devuelve ruta relativa

        $doc = DocumentoAnimal::query()->create([
            'id_animal' => $idAnimal,
            'nombre_original' => $originalName,
            'path' => $path,
            'notas' => $request->input('notas'),
        ]);

        $pk = $doc->getKey();

        return response()->json([
            'status' => 'success',
            'data' => [
                // ✅ para que el front tenga un id válido
                'id' => $pk,
                'id_documento' => $pk,

                'id_animal' => $doc->id_animal,
                'nombre_original' => $doc->nombre_original,
                'path' => $doc->path,
                'notas' => $doc->notas,
                'created_at' => optional($doc->created_at)->toISOString(),
                'updated_at' => optional($doc->updated_at)->toISOString(),
                'url' => asset('storage/' . ltrim((string) $doc->path, '/')),
            ],
        ], 201);
    }

    // DELETE /api/v1/documentos/{id}
    public function destroy($id)
    {
        // ✅ borrar por PK real, sin depender de columnas "id"
        $doc = DocumentoAnimal::query()
            ->where('id_documento', (int) $id)
            ->first();

        if (!$doc) {
            return response()->json([
                'status' => 'error',
                'message' => 'Documento no encontrado',
            ], 404);
        }

        // Borra del disco si existe
        $path = (string) ($doc->path ?? '');
        if ($path !== '' && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $doc->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Documento eliminado',
        ]);
    }
}