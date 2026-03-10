<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

class DocumentoAnimal extends Model
{
    protected $table = 'documentos_animales';
    protected $primaryKey = 'id_documento';
    
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_animal',
        'nombre_original',
        'path',
        'notas',
    ];

    protected $casts = [
        'id_animal' => 'integer',
    ];
}