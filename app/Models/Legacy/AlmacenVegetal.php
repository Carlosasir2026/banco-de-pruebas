<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

class AlmacenVegetal extends Model
{
    protected $table = 'almacen_vegetal';
    protected $primaryKey = 'ing_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'categoria',
        'nombre',
    ];

    protected $casts = [
        'ing_id' => 'integer',
        'categoria' => 'string',
        'nombre' => 'string',
    ];
}