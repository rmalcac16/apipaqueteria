<?php

namespace Modules\Comprobantes\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComprobanteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'envio_id' => 'required|exists:envios,id',
            'tipo_comprobante' => 'required|in:factura,boleta',
            'serie' => 'required|string|max:4',
            'cliente_tipo_documento' => 'required|string|max:5',
            'cliente_numero_documento' => 'required|string|max:20',
            'cliente_nombre' => 'required|string|max:255',
            'fecha_emision' => 'required|date',
            'moneda' => 'required|string|max:3',
            'total' => 'required|numeric|min:0',
        ];
    }
}
