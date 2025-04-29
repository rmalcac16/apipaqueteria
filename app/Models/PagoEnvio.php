<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoEnvio extends Model
{
    protected $fillable = [
        'envio_id',
        'monto',
        'estado',
        'metodo_pago',
        'medio_pago',
        'numero_transaccion',
        'fecha_pago',
        'realizado_por',
        'cobrado_por',
        'agencia_id',
        'observaciones',
    ];

    public function envio()
    {
        return $this->belongsTo(Envio::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'realizado_por');
    }

    public function cobrador()
    {
        return $this->belongsTo(User::class, 'cobrado_por');
    }

    public function agencia()
    {
        return $this->belongsTo(Agencia::class);
    }

    public function comprobante()
    {
        return $this->hasOne(Comprobante::class);
    }
}
