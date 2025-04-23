<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuotaComprobante extends Model
{
    protected $table = 'cuotas_comprobante';

    protected $fillable = [
        'comprobante_id',
        'numero_cuota',
        'monto',
        'fecha_vencimiento',
        'estado',
    ];

    public function comprobante()
    {
        return $this->belongsTo(Comprobante::class);
    }
}
