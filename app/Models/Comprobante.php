<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    protected $fillable = [
        'pago_envio_id',
        'tipo',
        'serie',
        'numero',
        'forma_pago',
        'monto_total',
        'estado',
        'estado_sunat',
        'xml_path',
        'cdr_path',
        'pdf_path',
        'fecha_emision',
        'cliente_id',
    ];

    public function pago()
    {
        return $this->belongsTo(PagoEnvio::class, 'pago_envio_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function cuotas()
    {
        return $this->hasMany(CuotaComprobante::class);
    }
}
