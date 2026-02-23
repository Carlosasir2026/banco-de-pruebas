<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreAnimalRequest;
use App\Http\Requests\Api\V1\UpdateAnimalRequest;
use App\Models\Legacy\Animal;
use Illuminate\Http\Request;

class AnimalesController extends Controller
{
    // GET /api/v1/animales?id_user=1
    public function index(Request $request)
    {
        $request->validate([
            'id_user' => ['required','integer'],
        ]);

        $rows = Animal::query()
            ->where('id_user', $request->integer('id_user'))
            ->orderByDesc('id_animal')
            ->get();

        return response()->json([
            'status' => 'success',
            'animales' => $rows,
        ]);
    }

    // GET /api/v1/animales/{id}
    public function show(int $id)
    {
        $animal = Animal::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'animal' => $animal,
        ]);
    }

    // POST /api/v1/animales
    public function store(StoreAnimalRequest $request)
    {
        $animal = Animal::create($request->validated());

        return response()->json([
            'status' => 'success',
            'animal' => $animal,
        ], 201);
    }

    // PUT /api/v1/animales/{id}
    public function update(UpdateAnimalRequest $request, int $id)
    {
        $animal = Animal::findOrFail($id);
        $animal->fill($request->validated());
        $animal->save();

        return response()->json([
            'status' => 'success',
            'animal' => $animal,
        ]);
    }

    // DELETE /api/v1/animales/{id}
    public function destroy(int $id)
    {
        $animal = Animal::findOrFail($id);
        $animal->delete();

        return response()->json([
            'status' => 'success',
            'deleted' => true,
        ]);
    }
}