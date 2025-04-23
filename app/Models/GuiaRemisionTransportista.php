<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuiaRemisionTransportista extends Model
{
    protected $table = 'guia_remision_transportistas';

    protected $fillable = [
        'envio_id',
        'viaje_id',
        'codigo',
        'serie',
        'numero',
        'fecha_emision',
        'fecha_inicio_traslado',
        'estado',
        'estado_sunat',
        'pdf_path_a4',
        'pdf_path_ticket_80',
        'pdf_path_ticket_58',
        'xml_path',
        'cdr_path',
        'hash',
        'user_id',
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
        'fecha_inicio_traslado' => 'date',
    ];

    // 📦 Relación con el envío
    public function envio(): BelongsTo
    {
        return $this->belongsTo(Envio::class);
    }

    // 🚚 Relación con el viaje (opcional)
    public function viaje(): BelongsTo
    {
        return $this->belongsTo(Viaje::class);
    }

    // 👤 Usuario que generó la guía
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // 🔍 Scope por estado interno
    public function scopeEstado($query, string $estado)
    {
        return $query->where('estado', $estado);
    }

    // 🔍 Scope por estado SUNAT
    public function scopeEstadoSunat($query, string $estadoSunat)
    {
        return $query->where('estado_sunat', $estadoSunat);
    }

    public function documentosSustento()
    {
        return $this->hasMany(DocumentoSustentoGuiaTransportista::class, 'guia_remision_transportistas_id');
    }
}
