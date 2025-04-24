<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Luecano\NumeroALetras\NumeroALetras;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        'codigo_sunat',
        'descripcion_sunat',
        'xml_path',
        'cdr_path',
        'pdf_path',
        'fecha_emision',
        'cliente_id',
        'hash_code'
    ];


    protected $casts = [
        'fecha_emision' => 'datetime',
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

    public function getQrTextAttribute(): string
    {
        return implode('|', [
            env('BUSINESS_RUC'),
            $this->tipo,
            $this->serie,
            $this->numero,
            number_format(($this->monto_total * 0.18), 2, '.', ''),
            number_format($this->monto_total, 2, '.', ''),
            \Carbon\Carbon::parse($this->fecha_emision)->format('Y-m-d'),
            $this->cliente->tipoDocumento ?? '',
            $this->cliente->numeroDocumento ?? '',
            $this->hash_code ?? '',
        ]);
    }


    // Monto Total a letras

    public function getMontoTotalLetrasAttribute(): string
    {
        $formatter = new NumeroALetras();
        return $formatter->toMoney($this->monto_total, 2, 'SOLES', 'CENTIMOS');
    }
}
