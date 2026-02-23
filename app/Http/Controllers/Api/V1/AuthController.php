<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Legacy\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = Usuario::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->contra_cif)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'id_user' => $user->id_user,
            'nombre'  => $user->nombre,
        ]);
    }
}