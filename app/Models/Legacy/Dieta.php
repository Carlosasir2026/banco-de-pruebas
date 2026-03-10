<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

class Dieta extends Model
{
    protected $table = 'dietas';
    protected $primaryKey = 'id_dieta';
    public $incrementing = true;
    protected $keyType = 'int';

    // Si tu tabla NO tiene updated_at:
    const UPDATED_AT = null;

    // Si tu tabla tampoco tiene created_at, añade:
    // const CREATED_AT = null;

    protected $fillable = [
        'id_animal',
        'nombre',
        'ingredientes',
    ];

    protected $casts = [
        'id_animal' => 'integer',

        // ✅ CLAVE: ingredientes se guarda/lee como JSON (array PHP)
        'ingredientes' => 'array',
    ];
}