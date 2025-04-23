<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehiculo extends Model
{
    protected $table = 'vehiculos';

    protected $fillable = [
        'tipo',
        'placa',
        'tuc',
        'marca',
        'modelo',
        'anio',
        'capacidadKg',
        'volumenM3',
        'estado',
        'acopladoA_id',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'capacidadKg' => 'float',
        'volumenM3' => 'float',
        'anio' => 'integer',
    ];

    /**
     * 🚚 Vehículo al que está acoplado (por ejemplo: un tractocamión)
     */
    public function acopladoA(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class, 'acopladoA_id');
    }

    /**
     * 🚛 Vehículo enganchado a este (solo si este es un tractocamión)
     */
    public function enganchado(): HasOne
    {
        return $this->hasOne(Vehiculo::class, 'acopladoA_id');
    }

    /**
     * Scope para tractocamiones
     */
    public function scopeTractocamiones($query)
    {
        return $query->where('tipo', 'tractocamion');
    }

    /**
     * Scope para remolques y semirremolques
     */
    public function scopeCarretas($query)
    {
        return $query->whereIn('tipo', ['remolque', 'semirremolque']);
    }
}
