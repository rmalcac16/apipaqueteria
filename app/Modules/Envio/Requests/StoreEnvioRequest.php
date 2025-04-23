<?php

namespace Modules\Envio\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEnvioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'numeroOrden'            => 'required|numeric|unique:envios,numeroOrden',
            'codigo'                 => 'required|string|max:50|unique:envios,codigo',
            'fechaEmision'           => 'nullable|date',
            'fechaTraslado'          => 'required|date|after_or_equal:today',

            'remitente_id'           => 'nullable|exists:clientes,id',
            'destinatario_id'        => 'nullable|exists:clientes,id',
            'pagador_id'             => 'nullable|exists:clientes,id',

            'agencia_origen_id'      => 'required|exists:agencias,id',
            'agencia_destino_id'     => 'required|exists:agencias,id|different:agencia_origen_id',

            'pesoTotal'              => 'nullable|numeric|min:0',
            'unidadMedida'           => ['nullable', Rule::in(['KGM', 'TN', 'LBR', 'OZ'])],
            'observaciones'          => 'nullable|string',

            // Recojo a domicilio
            'recojoDomicilio'        => 'boolean',
            'recojo_direccion'       => 'required_if:recojoDomicilio,true|string|max:255',
            'recojo_ubigeo'          => 'required_if:recojoDomicilio,true|string|size:6',
            'recojo_referencia'      => 'nullable|string|max:255',
            'recojo_telefono'        => 'required_if:recojoDomicilio,true|string|max:20',

            // Entrega a domicilio
            'entregaDomicilio'       => 'boolean',
            'entrega_direccion'      => 'required_if:entregaDomicilio,true|string|max:255',
            'entrega_ubigeo'         => 'required_if:entregaDomicilio,true|string|size:6',
            'entrega_referencia'     => 'nullable|string|max:255',
            'entrega_telefono'       => 'required_if:entregaDomicilio,true|string|max:20',

            // Ítems
            'items'                  => 'required|array|min:1',
            'items.*.cantidad'       => 'required|integer|min:1',
            'items.*.unidad_medida'  => ['required', Rule::in(['NIU', 'KGM', 'TN', 'LBR', 'OZ', 'CJ', 'BL', 'UND'])],
            'items.*.codigo'         => 'nullable|string|max:50',
            'items.*.descripcion'    => 'required|string|max:500',
        ];
    }

    public function attributes(): array
    {
        return [
            'numeroOrden'             => 'número de orden',
            'codigo'                  => 'código del envío',
            'fechaEmision'            => 'fecha de emisión',
            'fechaTraslado'           => 'fecha de traslado',
            'remitente_id'            => 'remitente',
            'destinatario_id'         => 'destinatario',
            'pagador_id'              => 'pagador',
            'agencia_origen_id'       => 'agencia de origen',
            'agencia_destino_id'      => 'agencia de destino',
            'pesoTotal'               => 'peso total',
            'unidadMedida'            => 'unidad de medida',
            'observaciones'           => 'observaciones',

            'recojoDomicilio'         => 'recojo a domicilio',
            'recojo_direccion'        => 'dirección de recojo',
            'recojo_ubigeo'           => 'ubigeo de recojo',
            'recojo_referencia'       => 'referencia de recojo',
            'recojo_telefono'         => 'teléfono de recojo',

            'entregaDomicilio'        => 'entrega a domicilio',
            'entrega_direccion'       => 'dirección de entrega',
            'entrega_ubigeo'          => 'ubigeo de entrega',
            'entrega_referencia'      => 'referencia de entrega',
            'entrega_telefono'        => 'teléfono de entrega',

            'items'                   => 'ítems del envío',
            'items.*.cantidad'        => 'cantidad del ítem',
            'items.*.unidad_medida'   => 'unidad de medida del ítem',
            'items.*.codigo'          => 'código del ítem',
            'items.*.descripcion'     => 'descripción del ítem',
        ];
    }
}
