<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

class AlmacenCarne extends Model
{
    protected $table = 'almacen_carne';
    protected $primaryKey = 'id_alimento';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'animal',
        'pieza',
    ];

    protected $casts = [
        'id_alimento' => 'integer',
        'animal' => 'string',
        'pieza' => 'string',
    ];
}