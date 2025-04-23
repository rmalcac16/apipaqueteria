<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'tipoDocumento',
        'numeroDocumento',
        'nombreCompleto',
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
