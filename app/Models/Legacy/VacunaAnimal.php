<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

class VacunaAnimal extends Model
{
    protected $table = 'vacunas_animales';

    protected $fillable = [
        'id_animal',
        'vacuna',
        'fecha',
        'clinica_vet',
        'lote',
        'notas',
    ];

    protected $casts = [
        'id_animal' => 'integer',
        'fecha' => 'date:Y-m-d',
    ];
}