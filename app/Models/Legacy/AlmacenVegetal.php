<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

class AlmacenVegetal extends Model
{
    protected $table = 'almacen_vegetal';
    protected $primaryKey = 'id_alimento';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'categoria',
        'nombre',
    ];

    protected $casts = [
        'id_alimento' => 'integer',
        'categoria' => 'string',
        'nombre' => 'string',
    ];
}