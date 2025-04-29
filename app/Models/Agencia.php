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
        'googleMapsUrl',
        'imagenUrl',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    protected $appends = ['imagenUrlPublica'];

    public function getImagenUrlPublicaAttribute()
    {
        return $this->imagenUrl ? asset('storage/' . $this->imagenUrl) : null;
    }

    public function envios()
    {
        return $this->hasMany(Envio::class);
    }
}
