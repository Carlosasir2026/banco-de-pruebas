<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

class Dieta extends Model
{
    protected $table = 'dietas';
    protected $primaryKey = 'id_dieta';
    public $incrementing = true;
    protected $keyType = 'int';

    const UPDATED_AT = null;

    protected $fillable = [
        'id_animal',
        'nombre',
        'ingredientes',
    ];

    protected $casts = [
        'id_animal' => 'integer',
    ];

    public function setIngredientesAttribute($value): void
    {
        $this->attributes['ingredientes'] = $this->toPgArrayLiteral($value);
    }

    public function getIngredientesAttribute($value): array
    {
        return $this->fromPgArrayLiteral($value);
    }

    private function toPgArrayLiteral($value): ?string
    {
        if ($value === null) return null;

        if (is_string($value)) return $value;

        if (!is_array($value)) return null;

        $items = array_map(function ($v) {
            $s = (string) $v;
            $s = str_replace(['\\', '"'], ['\\\\', '\\"'], $s);
            return '"' . $s . '"';
        }, $value);

        return '{' . implode(',', $items) . '}';
    }

    private function fromPgArrayLiteral($value): array
    {
        if ($value === null || $value === '{}') return [];

        $trim = trim($value);
        if ($trim[0] === '{' && substr($trim, -1) === '}') {
            $inner = substr($trim, 1, -1);
            if ($inner === '') return [];

            $parts = array_map('trim', explode(',', $inner));
            $parts = array_map(function ($p) {
                $p = trim($p);
                if ($p === 'NULL') return null;
                if (strlen($p) >= 2 && $p[0] === '"' && substr($p, -1) === '"') {
                    $p = substr($p, 1, -1);
                }
                $p = str_replace(['\\"', '\\\\'], ['"', '\\'], $p);
                return $p;
            }, $parts);

            return array_values(array_filter($parts, fn($x) => $x !== null));
        }

        return [];
    }
}