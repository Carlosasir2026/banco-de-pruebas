<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

class Alimento extends Model
{
    protected $table = 'alimentos';
    protected $primaryKey = 'id_alimento';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'id_alimento' => 'integer',
        'precio' => 'decimal:2',
        'calorias' => 'decimal:2',
        'proteina_bruta' => 'decimal:2',
        'grasa_bruta' => 'decimal:2',
        'humedad' => 'decimal:2',
        'cenizas' => 'decimal:2',
        'fibra' => 'decimal:2',
    ];
}
