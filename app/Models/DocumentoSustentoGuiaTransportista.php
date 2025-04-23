<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoSustentoGuiaTransportista extends Model
{
    protected $fillable = [
        'guia_remision_transportistas_id',
        'tipo_documento',
        'serie_numero',
        'ruc_emisor',
    ];

    public function guia()
    {
        return $this->belongsTo(GuiaRemisionTransportista::class, 'guia_remision_transportistas_id');
    }
}
