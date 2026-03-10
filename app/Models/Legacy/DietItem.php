<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

class DietItem extends Model
{
    protected $table = 'diet_items';
    protected $primaryKey = 'id_diet_item';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_dieta',
        'ingrediente',
        'gramos',
        'id_alimento',
    ];

    protected $casts = [
        'id_dieta' => 'integer',
        'id_alimento' => 'integer',
        'gramos' => 'decimal:2',
    ];
}