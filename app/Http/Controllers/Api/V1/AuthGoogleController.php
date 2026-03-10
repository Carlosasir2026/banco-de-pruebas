<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Illuminate\Support\Str;
use App\Models\Legacy\Usuario;

class AuthGoogleController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'id_token' => ['required','string'],
        ]);

        $client = new GoogleClient([
            'client_id' => config('services.google.client_id'),
        ]);

        $payload = $client->verifyIdToken($request->id_token);

        if (!$payload) {
            return response()->json(['message' => 'Invalid Google token'], 401);
        }

        $email  = $payload['email'] ?? null;
        $nombre = $payload['given_name'] ?? ($payload['name'] ?? 'Usuario');

        if (!$email) {
            return response()->json(['message' => 'Google token missing email'], 422);
        }

        // Tu BD legacy tiene campos NOT NULL: dni y apellido_1 (y password legacy)
        $user = Usuario::query()->firstOrCreate(
            ['email' => $email],
            [
                'dni'        => 'GOOGLE_' . Str::upper(Str::random(10)),
                'nombre'     => $nombre,
                'apellido_1' => 'Google',
                'apellido_2' => '',
                'contra_cif' => Str::upper(Str::random(20)),
            ]
        );

        $token = $user->createToken('bioscan')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user,
        ]);
    }
}