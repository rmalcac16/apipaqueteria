<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Viaje extends Model
{
    use HasFactory;

    // 🔹 Constantes de estado
    public const ESTADO_PROGRAMADO = 'programado';
    public const ESTADO_EN_TRANSITO = 'en_transito';
    public const ESTADO_FINALIZADO = 'finalizado';
    public const ESTADO_CANCELADO = 'cancelado';

    // 🔹 Tipos de viaje
    public const TIPO_NORMAL = 'normal';
    public const TIPO_RETORNO = 'retorno';
    public const TIPO_INTERNO = 'interno';

    protected $table = 'viajes';

    protected $fillable = [
        'codigo',
        'user_id',
        'vehiculo_id',
        'conductor_id',
        'agencia_origen_id',
        'agencia_destino_id',
        'cantidad_envios',
        'peso_total_estimado',
        'volumen_total_estimado',
        'tipo_carga',
        'capacidad_usada',
        'capacidad_maxima_permitida',
        'fecha_salida',
        'fecha_llegada_estimada',
        'fecha_llegada_real',
        'hora_salida_programada',
        'hora_salida_real',
        'tiempo_estimado_viaje',
        'estado',
        'estado_legible',
        'aprobado_por',
        'firma_digital_conductor',
        'firma_digital_agencia_destino',
        'manifiesto_generado',
        'codigo_manifiesto',
        'viaje_urgente',
        'sincronizado_con_sunat',
        'tipo_viaje',
        'observaciones',
        'observaciones_salida',
        'observaciones_llegada',
    ];

    protected $casts = [
        'manifiesto_generado' => 'boolean',
        'viaje_urgente' => 'boolean',
        'sincronizado_con_sunat' => 'boolean',
        'fecha_salida' => 'datetime',
        'fecha_llegada_estimada' => 'datetime',
        'fecha_llegada_real' => 'datetime',
    ];

    // 🔹 Relaciones

    public function creador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function conductor()
    {
        return $this->belongsTo(User::class, 'conductor_id');
    }

    public function aprobador()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    public function agenciaOrigen()
    {
        return $this->belongsTo(Agencia::class, 'agencia_origen_id');
    }

    public function agenciaDestino()
    {
        return $this->belongsTo(Agencia::class, 'agencia_destino_id');
    }

    public function envios()
    {
        return $this->hasMany(Envio::class);
    }

    // 🔹 Scopes útiles

    public function scopePorEstado($query, string $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopeUrgentes($query)
    {
        return $query->where('viaje_urgente', true);
    }

    public function scopeEntreAgencias($query, int $origenId, int $destinoId)
    {
        return $query
            ->where('agencia_origen_id', $origenId)
            ->where('agencia_destino_id', $destinoId);
    }

    public function scopePorConductor($query, int $conductorId)
    {
        return $query->where('conductor_id', $conductorId);
    }
}
