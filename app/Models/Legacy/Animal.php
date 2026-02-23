<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $table = 'animales';

    protected $primaryKey = 'id_animal';
    public $incrementing = true;
    protected $keyType = 'int';

    const UPDATED_AT = null;

    protected $fillable = [
        'id_user',
        'nombre',
        'especie',
        'raza',
        'nacimiento_ano',
        'nacimiento_mes',
        'peso_kg',
        'actividad',
        'esterilizado',
        'apetito',
        'heces',
        'patologias',
        'alergias_detalle',
        'objetivo_kg',
    ];

    protected $casts = [
        'id_user' => 'integer',
        'nacimiento_ano' => 'integer',
        'nacimiento_mes' => 'integer',
        'peso_kg' => 'decimal:2',
        'objetivo_kg' => 'decimal:2',
        'esterilizado' => 'boolean',
        'especie' => 'string',
        'actividad' => 'string',
        'apetito' => 'string',
        // NO: 'patologias' => 'array',
    ];

    /**
     * Convierte array PHP -> literal Postgres {a,b}
     */
    public function setPatologiasAttribute($value): void
    {
        if ($value === null) {
            $this->attributes['patologias'] = null;
            return;
        }

        // Si ya viene como string "{...}" lo dejamos
        if (is_string($value)) {
            $this->attributes['patologias'] = $value;
            return;
        }

        if (!is_array($value)) {
            $this->attributes['patologias'] = null;
            return;
        }

        // Sanitiza: convierte a strings y escapa comillas/backslashes
        $items = array_map(function ($v) {
            $s = (string) $v;
            $s = str_replace(['\\', '"'], ['\\\\', '\\"'], $s);
            return '"' . $s . '"';
        }, $value);

        $this->attributes['patologias'] = '{' . implode(',', $items) . '}';
    }

    /**
     * Convierte literal Postgres "{a,b}" -> array PHP ["a","b"]
     */
    public function getPatologiasAttribute($value): array
    {
        if ($value === null || $value === '{}') {
            return [];
        }

        // value suele venir como {ninguna} o {"una","dos"}
        $trim = trim($value);

        if ($trim[0] === '{' && substr($trim, -1) === '}') {
            $inner = substr($trim, 1, -1);
            if ($inner === '') return [];

            // Parse simple (sirve para tus casos)
            // Quitamos comillas y separamos por coma no escapada (aquí asumimos sin comas dentro)
            $parts = array_map('trim', explode(',', $inner));
            $parts = array_map(function ($p) {
                $p = trim($p);
                if ($p === 'NULL') return null;
                // quita comillas si existen
                if (strlen($p) >= 2 && $p[0] === '"' && substr($p, -1) === '"') {
                    $p = substr($p, 1, -1);
                }
                $p = str_replace(['\\"', '\\\\'], ['"', '\\'], $p);
                return $p;
            }, $parts);

            // elimina nulls
            return array_values(array_filter($parts, fn($x) => $x !== null));
        }

        // Si por lo que sea llega en otro formato, devolvemos vacío
        return [];
    }
}