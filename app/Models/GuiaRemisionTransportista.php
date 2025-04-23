<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuiaRemisionTransportista extends Model
{
    protected $table = 'guia_remision_transportistas';

    protected $fillable = [
        'envio_id',
        'conductor_nombre',
        'conductor_tipo_documento',
        'conductor_documento',
        'conductor_licencia',
        'vehiculo_placa',
        'vehiculo_tuc',
        'vehiculo_certificado',
        'fecha_inicio_traslado',
        'modo_transporte',
        'punto_partida_ubigeo',
        'punto_partida_direccion',
        'punto_llegada_ubigeo',
        'punto_llegada_direccion',
        'descripcion_carga',
        'peso_total',
        'unidad_medida',
        'remitente_nombre',
        'remitente_documento',
        'destinatario_nombre',
        'destinatario_documento',
        'pagador_tipo',
        'pagador_tipo_documento',
        'pagador_numero_documento',
        'pagador_nombre_razon_social',
        'estado',
        'pdf_path',
    ];


    protected static function booted()
    {
        static::creating(function ($guia) {
            $envio = $guia->envio()->with('agencia')->first();
            $codigoAgencia = $envio->agencia->codigo; // Asegúrate que sea tipo '001'

            $ultimo = self::whereHas('envio', function ($q) use ($codigoAgencia) {
                $q->whereHas('agencia', function ($a) use ($codigoAgencia) {
                    $a->where('codigo', $codigoAgencia);
                });
            })->orderByDesc('id')->first();

            $correlativo = 1;
            if ($ultimo && preg_match('/^V\d{3}-(\d{6})$/', $ultimo->numero_documento, $match)) {
                $correlativo = (int) $match[1] + 1;
            }

            $guia->numero_documento = 'V' . str_pad($codigoAgencia, 3, '0', STR_PAD_LEFT) . '-' . str_pad($correlativo, 6, '0', STR_PAD_LEFT);
        });
    }


    public function envio()
    {
        return $this->belongsTo(Envio::class);
    }

    public function documentosSustento()
    {
        return $this->hasMany(DocumentoSustentoGuiaTransportista::class, 'guia_remision_transportistas_id');
    }
}
