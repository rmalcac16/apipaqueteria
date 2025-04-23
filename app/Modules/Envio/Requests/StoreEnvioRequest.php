<?php

namespace Modules\Envio\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnvioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fechaTraslado' => 'required|date|date_format:Y-m-d',

            'remitente_id' => 'required|exists:clientes,id',
            'destinatario_id' => 'required|exists:clientes,id',
            'pagador_id' => 'required|exists:clientes,id',

            'agencia_origen_id' => 'required|exists:agencias,id',
            'recojoDomicilio' => 'boolean',
            'recojo_direccion' => 'nullable|string|max:255',
            'recojo_ubigeo' => 'nullable|string|size:6',
            'recojo_referencia' => 'nullable|string|max:255',
            'recojo_telefono' => 'nullable|string|max:20',

            'agencia_destino_id' => 'required|exists:agencias,id',
            'entregaDomicilio' => 'boolean',
            'entrega_direccion' => 'nullable|string|max:255',
            'entrega_ubigeo' => 'nullable|string|size:6',
            'entrega_referencia' => 'nullable|string|max:255',
            'entrega_telefono' => 'nullable|string|max:20',

            'pesoTotal' => 'required|numeric|min:0',
            'unidadMedida' => 'required|in:KGM,TN,LBR,OZ',

            'observaciones' => 'nullable|string',

            'fleteAPagar' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'fechaTraslado.required' => 'La fecha de traslado es obligatoria.',
            'fechaTraslado.date' => 'La fecha de traslado no es una fecha válida.',
            'fechaTraslado.date_format' => 'La fecha de traslado no tiene el formato correcto.',

            'remitente_id.required' => 'El remitente es obligatorio.',
            'remitente_id.exists' => 'El remitente seleccionado no es válido.',
            'destinatario_id.required' => 'El destinatario es obligatorio.',
            'destinatario_id.exists' => 'El destinatario seleccionado no es válido.',
            'pagador_id.required' => 'El pagador es obligatorio.',
            'pagador_id.exists' => 'El pagador seleccionado no es válido.',

            'agencia_origen_id.required' => 'La agencia de origen es obligatoria.',
            'agencia_origen_id.exists' => 'La agencia de origen seleccionada no es válida.',

            'agencia_destino_id.required' => 'La agencia de destino es obligatoria.',
            'agencia_destino_id.exists' => 'La agencia de destino seleccionada no es válida.',

            'pesoTotal.required' => 'El peso total es obligatorio.',
            'pesoTotal.numeric' => 'El peso total debe ser un número.',
            'pesoTotal.min' => 'El peso total debe ser al menos 0.',

            'unidadMedida.required' => 'La unidad de medida es obligatoria.',
            'unidadMedida.in' => 'La unidad de medida seleccionada no es válida.',


            'fleteAPagar.required' => 'El flete a pagar es obligatorio.',
            'fleteAPagar.numeric' => 'El flete a pagar debe ser un número.',
            'fleteAPagar.min' => 'El flete a pagar debe ser al menos 0.',

        ];
    }

    public function attributes(): array
    {
        return [
            'fechaEmisión' => 'fecha de emisión',
            'fechaTraslado' => 'fecha de traslado',
            'remitente_id' => 'remitente',
            'destinatario_id' => 'destinatario',
            'pagador_id' => 'pagador',
            'agencia_origen_id' => 'agencia de origen',
            'agencia_destino_id' => 'agencia de destino',
            'recojo_direccion' => 'dirección de recojo',
            'recojo_ubigeo' => 'ubigeo de recojo',
            'recojo_referencia' => 'referencia de recojo',
            'recojo_telefono' => 'teléfono de recojo',
            'entrega_direccion' => 'dirección de entrega',
            'entrega_ubigeo' => 'ubigeo de entrega',
            'entrega_referencia' => 'referencia de entrega',
            'entrega_telefono' => 'teléfono de entrega',
            'pesoTotal' => 'peso total',
            'unidadMedida' => 'unidad de medida',
            'observaciones' => 'observaciones',
            'fleteAPagar' => 'flete a pagar',
        ];
    }
}
