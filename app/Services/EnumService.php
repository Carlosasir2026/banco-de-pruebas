<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class EnumService
{
    public function values(string $enumType): array
    {
        // Seguridad: solo nombres de tipo válidos
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $enumType)) {
            throw new InvalidArgumentException("Enum type inválido: {$enumType}");
        }

        return Cache::remember("pg_enum_values:{$enumType}", 3600, function () use ($enumType) {
            $rows = DB::select("select unnest(enum_range(null::{$enumType})) as value");
            return array_map(fn ($r) => $r->value, $rows);
        });
    }
}