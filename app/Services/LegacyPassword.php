<?php

namespace App\Services;

class LegacyPassword
{
    /**
     * Ajusta aquí el modo real de tu sistema viejo.
     * Por defecto: bcrypt (password_hash / password_verify)
     */
    public function hash(string $plain): string
    {
        return password_hash($plain, PASSWORD_BCRYPT);
    }

    public function verify(string $plain, string $hashedOrPlain): bool
    {
        // Si es bcrypt válido
        if (str_starts_with($hashedOrPlain, '$2y$') || str_starts_with($hashedOrPlain, '$2a$') || str_starts_with($hashedOrPlain, '$2b$')) {
            return password_verify($plain, $hashedOrPlain);
        }

        // fallback: si legacy guardaba en texto plano
        return hash_equals($hashedOrPlain, $plain);
    }
}