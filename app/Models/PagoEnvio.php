<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PagoEnvio extends Model
{
    use HasFactory;

    protected $table = 'pago_envios';

    protected $fillable = [
        'envio_id',
        'monto',
        'estado',
        'forma_pago',
        'medio_pago',
        'numero_transaccion',
        'fecha_pago',
        'realizado_por',
        'cobrado_por',
        'agencia_id',
        'observaciones',
    ];

    protected $casts = [
        'fecha_pago' => 'datetime',
        'monto' => 'decimal:2',
    ];

    // 🔗 Envío asociado
    public function envio()
    {
        return $this->belongsTo(Envio::class);
    }

    // 🔗 Cliente que realizó el pago
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'realizado_por');
    }

    // 🔗 Usuario que cobró el pago (agente o admin)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'cobrado_por');
    }

    // 🔗 Agencia donde se registró el pago
    public function agencia()
    {
        return $this->belongsTo(Agencia::class);
    }

    // 🔗 Comprobante de pago
    public function comprobante()
    {
        return $this->hasOne(Comprobante::class, 'pago_envios_id');
    }
}
