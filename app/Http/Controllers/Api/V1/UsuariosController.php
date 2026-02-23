<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Requests\Api\V1\UpdateUsuarioRequest;
use App\Http\Requests\Api\V1\ChangePasswordRequest;
use App\Models\Legacy\Usuario;
use App\Services\LegacyPassword;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    // GET /api/v1/usuarios/{id}
    public function show(int $id)
    {
        $u = Usuario::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'usuario' => $u,
        ]);
    }

    // GET /api/v1/usuarios/by-email?email=...
    public function byEmail(Request $request)
    {
        $request->validate(['email' => ['required','email']]);

        $u = Usuario::where('email', $request->string('email'))->first();

        return response()->json([
            'status' => 'success',
            'exists' => (bool) $u,
            'usuario' => $u,
        ]);
    }

    // POST /api/v1/register
    public function register(RegisterRequest $request, LegacyPassword $pwd)
    {
        $data = $request->validated();

        $email = strtolower(trim($data['email']));

        if (Usuario::where('email', $email)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'El email ya está registrado',
            ], 422);
        }

        $u = Usuario::create([
            'dni' => strtoupper(trim($data['dni'])),
            'nombre' => $data['nombre'],
            'apellido_1' => $data['apellido_1'] ?? null,
            'apellido_2' => $data['apellido_2'] ?? null,
            'email' => $email,
            'contra_cif' => $pwd->hash($data['password']),
        ]);

        return response()->json([
            'status' => 'success',
            'usuario' => $u,
        ], 201);
    }

    // PUT /api/v1/usuarios/{id}
    public function update(UpdateUsuarioRequest $request, int $id)
    {
        $u = Usuario::findOrFail($id);

        $data = $request->validated();

        if (isset($data['email'])) {
            $data['email'] = strtolower(trim($data['email']));

            $emailTaken = Usuario::where('email', $data['email'])
                ->where('id_user', '!=', $id)
                ->exists();

            if ($emailTaken) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ese email ya lo usa otro usuario',
                ], 422);
            }
        }

        $u->fill($data);
        $u->save();

        return response()->json([
            'status' => 'success',
            'usuario' => $u,
        ]);
    }

    // POST /api/v1/change-password
    public function changePassword(ChangePasswordRequest $request, LegacyPassword $pwd)
    {
        $data = $request->validated();
        $email = strtolower(trim($data['email']));

        $u = Usuario::where('email', $email)->first();

        if (!$u) {
            return response()->json([
                'status' => 'error',
                'message' => 'Usuario no encontrado',
            ], 404);
        }

        if (!$pwd->verify($data['current_password'], (string) $u->contra_cif)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Contraseña actual incorrecta',
            ], 401);
        }

        $u->contra_cif = $pwd->hash($data['new_password']);
        $u->save();

        return response()->json([
            'status' => 'success',
            'changed' => true,
        ]);
    }
}