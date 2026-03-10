<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

class CitaVet extends Model
{
    protected $table = 'citas_vet';
    protected $primaryKey = 'id_cita';

    public $timestamps = false;

    protected $fillable = [
        'id_animal',
        'fecha',
        'hora',
        'clinica_vet',
        'telefono',
        'notas',
        'created_at',
    ];

    protected $casts = [
        'fecha' => 'date:Y-m-d',
    ];
}