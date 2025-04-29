<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Envio extends Model
{
    use HasFactory;

    protected $fillable = [
        'fechaEmision',
        'fechaTraslado',
        'user_id',
        'remitente_id',
        'destinatario_id',
        'pagador_id',
        'viaje_id',
        'agencia_origen_id',
        'recojoDomicilio',
        'recojo_direccion',
        'recojo_ubigeo',
        'recojo_distrito',
        'recojo_provincia',
        'recojo_departamento',
        'recojo_referencia',
        'recojo_telefono',
        'agencia_destino_id',
        'entregaDomicilio',
        'entrega_direccion',
        'entrega_ubigeo',
        'entrega_distrito',
        'entrega_provincia',
        'entrega_departamento',
        'entrega_referencia',
        'entrega_telefono',
        'pesoTotal',
        'unidadMedida',
        'valorDeclarado',
        'esFragil',
        'esPeligroso',
        'costoEnvio',
        'formaPago',
        'observaciones',
        'pdf_path_a4',
        'pdf_path_ticket_80',
        'pdf_path_ticket_58',
    ];

    protected static function booted()
    {
        static::creating(function ($envio) {
            $lastOrder = static::max('numeroOrden') ?? 0;
            $envio->numeroOrden = str_pad($lastOrder + 1, 8, '0', STR_PAD_LEFT);

            do {
                $codigo = strtoupper(Str::random(4));
            } while (static::where('codigo', $codigo)->exists());

            $envio->codigo = $codigo;
            $envio->fechaEmision = now();
        });
    }

    public function remitente()
    {
        return $this->belongsTo(Cliente::class, 'remitente_id');
    }

    public function destinatario()
    {
        return $this->belongsTo(Cliente::class, 'destinatario_id');
    }

    public function pagador()
    {
        return $this->belongsTo(Cliente::class, 'pagador_id');
    }

    public function agenciaOrigen()
    {
        return $this->belongsTo(Agencia::class, 'agencia_origen_id');
    }

    public function agenciaDestino()
    {
        return $this->belongsTo(Agencia::class, 'agencia_destino_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pago()
    {
        return $this->hasOne(PagoEnvio::class);
    }

    public function seguimiento()
    {
        return $this->hasMany(SeguimientoEnvio::class);
    }

    public function items()
    {
        return $this->hasMany(EnvioItem::class);
    }


    public function guiaRemision()
    {
        return $this->hasOne(GuiaRemisionTransportista::class, 'envio_id');
    }
}
