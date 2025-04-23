<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comprobante extends Model
{
    use HasFactory;

    protected $fillable = [
        'pagoEnviosId',
        'tipoComprobante',
        'serie',
        'correlativo',
        'clienteTipoDocumento',
        'clienteNumeroDocumento',
        'clienteNombre',
        'clienteDireccion',
        'detalleServicio',
        'valorUnitario',
        'igv',
        'precioUnitario',
        'montoTotal',
        'unidadMedida',
        'codigoProducto',
        'tipoAfectacionIgv',
        'fechaEmision',
        'moneda',
        'tipoPago',
        'estado',
        'pdfPathA4',
        'pdfPathTicket80',
        'pdfPathTicket58',
        'sunatEstado',
        'sunatCodigoError',
        'sunatMensajeError',
        'sunatCdrPath',
        'xmlPath',
        'xmlHash',
    ];


    public function cuotas()
    {
        return $this->hasMany(ComprobanteCuota::class, 'comprobanteId');
    }

    public function pagoEnvio()
    {
        return $this->belongsTo(PagoEnvio::class, 'pagoEnviosId');
    }
}
