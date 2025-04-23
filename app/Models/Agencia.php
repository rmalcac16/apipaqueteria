<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agencia extends Model
{
    protected $table = 'agencias';

    protected $fillable = [
        'nombre',
        'codigo',
        'correo',
        'direccion',
        'telefono',
        'ubigeo',
        'distrito',
        'provincia',
        'departamento',
        'estado',
        'googleMapsUrl'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function envios()
    {
        return $this->hasMany(Envio::class);
    }
}
