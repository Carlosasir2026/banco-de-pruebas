<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_user';
    public $incrementing = true;
    protected $keyType = 'int';

    const UPDATED_AT = null;

    protected $fillable = [
        'dni',
        'nombre',
        'apellido_1',
        'apellido_2',
        'email',
        'contra_cif',
    ];

    protected $hidden = [
        'contra_cif',
    ];
}