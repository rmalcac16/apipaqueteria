<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SerieComprobante extends Model
{
    protected $fillable = [
        'tipo_comprobante',
        'serie',
        'descripcion',
        'estado',
        'sunat_origen',
    ];
}
