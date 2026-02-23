<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

class AlmacenCarne extends Model
{
    protected $table = 'almacen_carne';
    protected $primaryKey = 'ing_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'animal',
        'pieza',
    ];

    protected $casts = [
        'ing_id' => 'integer',
        'animal' => 'string',
        'pieza' => 'string',
    ];
}