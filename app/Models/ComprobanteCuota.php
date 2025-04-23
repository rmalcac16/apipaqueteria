<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComprobanteCuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'comprobanteId',
        'numeroCuota',
        'monto',
        'fechaVencimiento',
        'pagado',
        'fechaPago',
    ];

    public function comprobante()
    {
        return $this->belongsTo(Comprobante::class, 'comprobanteId');
    }
}
