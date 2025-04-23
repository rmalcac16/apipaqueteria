<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $fillable = [
        'tipo',
        'placa',
        'tuc',
        'marca',
        'modelo',
        'anio',
        'capacidad_kg',
        'volumen_m3',
        'estado',
        'carreta_id',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'capacidad_kg' => 'decimal:2',
        'volumen_m3' => 'decimal:2',
    ];

    public function carreta()
    {
        return $this->belongsTo(Vehiculo::class, 'carreta_id');
    }

    public function montadoEn()
    {
        return $this->hasOne(Vehiculo::class, 'carreta_id');
    }
}
