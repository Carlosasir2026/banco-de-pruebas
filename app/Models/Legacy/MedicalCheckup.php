<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

class MedicalCheckup extends Model
{
    protected $table = 'medical_checkups';
    protected $primaryKey = 'id_checkup';

    public $timestamps = true;

    protected $fillable = [
        'id_animal',
        'fecha_consulta',
        'tipo_consulta',
        'motivo',
        'estado_general',
        'tratamiento',
        'medicacion',
        'observaciones',
    ];

    protected $casts = [
        'fecha_consulta' => 'date:Y-m-d',
    ];
}