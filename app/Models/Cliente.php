<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'tipo_documento',
        'documento',
        'nombre_completo',
        'direccion',
        'telefono',
        'correo',
        'frecuente',
        'observaciones'
    ];

    protected $casts = [
        'frecuente' => 'boolean',
    ];
}
