<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Viaje extends Model
{
    protected $table = 'viajes';

    protected $fillable = [
        'codigo',
        'user_id',
        'vehiculo_principal_id',
        'vehiculo_secundario_id',
        'conductor_principal_id',
        'conductor_secundario_id',
        'agencia_origen_id',
        'agencia_destino_id',
        'fecha_salida',
        'estado',
    ];

    protected $casts = [
        'fecha_salida' => 'datetime',
    ];

    // Quien creó el viaje
    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Vehículo principal (tractocamión)
    public function vehiculoPrincipal(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_principal_id');
    }

    // Vehículo secundario (remolque o semirremolque)
    public function vehiculoSecundario(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_secundario_id');
    }

    // Conductor principal
    public function conductorPrincipal(): BelongsTo
    {
        return $this->belongsTo(User::class, 'conductor_principal_id');
    }

    // Conductor secundario (opcional)
    public function conductorSecundario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'conductor_secundario_id');
    }

    // Agencia origen
    public function agenciaOrigen(): BelongsTo
    {
        return $this->belongsTo(Agencia::class, 'agencia_origen_id');
    }

    // Agencia destino
    public function agenciaDestino(): BelongsTo
    {
        return $this->belongsTo(Agencia::class, 'agencia_destino_id');
    }
}
