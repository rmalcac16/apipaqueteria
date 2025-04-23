<?php

namespace Modules\GuiaRemisionTransportista\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuiaRemisionTransportistaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'envio_id' => 'required|exists:envios,id',
            'fecha_inicio_traslado' => 'required|date',
            'pagador_tipo' => 'required|in:remitente,destinatario,tercero',
            'pagador_tipo_documento' => 'required_if:pagador_tipo,tercero',
            'pagador_numero_documento' => 'required_if:pagador_tipo,tercero',
            'pagador_nombre_razon_social' => 'required_if:pagador_tipo,tercero',

            'documentos_sustento' => 'array|nullable',
            'documentos_sustento.*.tipo_documento' => 'required|string',
            'documentos_sustento.*.serie_numero' => 'required|string',
            'documentos_sustento.*.ruc_emisor' => 'required|string',
        ];
    }
}
