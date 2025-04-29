<?php

namespace Modules\Envio\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEnvioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fechaTraslado'          => 'required|date|after_or_equal:today',

            'remitente_id'           => 'required|exists:clientes,id',
            'destinatario_id'        => 'required|exists:clientes,id',
            'pagador_id'             => 'required|exists:clientes,id',

            'agencia_origen_id'      => 'required|exists:agencias,id',
            'agencia_destino_id'     => 'required|exists:agencias,id|different:agencia_origen_id',

            'pesoTotal'              => 'nullable|numeric|min:0',
            'unidadMedida'           => ['nullable', Rule::in(['KGM', 'TN', 'LBR', 'OZ'])],
            'valorDeclarado'         => 'nullable|numeric|min:0',
            'esFragil'               => 'boolean',
            'esPeligroso'            => 'boolean',
            'costoEnvio'             => 'nullable|numeric|min:0',
            'observaciones'          => 'nullable|string',

            // Recojo a domicilio
            'recojoDomicilio'        => 'boolean',
            'recojo_direccion'       => 'required_if:recojoDomicilio,true|string|max:255',
            'recojo_ubigeo'          => 'required_if:recojoDomicilio,true|string|size:6',
            'recojo_distrito'        => 'required_if:recojoDomicilio,true|string|max:255',
            'recojo_provincia'       => 'required_if:recojoDomicilio,true|string|max:255',
            'recojo_departamento'    => 'required_if:recojoDomicilio,true|string|max:255',
            'recojo_referencia'      => 'nullable|string|max:255',
            'recojo_telefono'        => 'required_if:recojoDomicilio,true|string|max:20',

            // Entrega a domicilio
            'entregaDomicilio'       => 'boolean',
            'entrega_direccion'      => 'required_if:entregaDomicilio,true|string|max:255',
            'entrega_ubigeo'         => 'required_if:entregaDomicilio,true|string|size:6',
            'entrega_distrito'       => 'required_if:entregaDomicilio,true|string|max:255',
            'entrega_provincia'      => 'required_if:entregaDomicilio,true|string|max:255',
            'entrega_departamento'   => 'required_if:entregaDomicilio,true|string|max:255',
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

    public function messages(): array
    {
        return [
            'fechaTraslado.required'           => 'La fecha de traslado es requerida.',
            'fechaTraslado.date'               => 'La fecha de traslado debe ser una fecha válida.',
            'fechaTraslado.after_or_equal'     => 'La fecha de traslado debe ser hoy o una fecha posterior.',

            'remitente_id.required'            => 'El remitente es requerido.',
            'remitente_id.exists'              => 'El remitente seleccionado no es válido.',

            'destinatario_id.required'         => 'El destinatario es requerido.',
            'destinatario_id.exists'           => 'El destinatario seleccionado no es válido.',

            'pagador_id.required'              => 'El pagador es requerido.',
            'pagador_id.exists'                => 'El pagador seleccionado no es válido.',

            'agencia_origen_id.required'       => 'La agencia de origen es requerida.',
            'agencia_origen_id.exists'         => 'La agencia de origen seleccionada no es válida.',

            'agencia_destino_id.required'      => 'La agencia de destino es requerida.',
            'agencia_destino_id.exists'        => 'La agencia de destino seleccionada no es válida.',
            'agencia_destino_id.different'     => 'La agencia de destino debe ser diferente de la agencia de origen.',

            'pesoTotal.numeric'                => 'El peso total debe ser un número.',
            'pesoTotal.min'                    => 'El peso total no puede ser menor a 0.',

            'unidadMedida.in'                  => 'La unidad de medida seleccionada no es válida.',

            'valorDeclarado.numeric'           => 'El valor declarado debe ser un número.',
            'valorDeclarado.min'               => 'El valor declarado no puede ser menor a 0.',

            'esFragil.boolean'                 => 'El campo "Es Frágil" debe ser verdadero o falso.',
            'esPeligroso.boolean'              => 'El campo "Es Peligroso" debe ser verdadero o falso.',

            'costoEnvio.numeric'               => 'El costo de envío debe ser un número.',
            'costoEnvio.min'                   => 'El costo de envío no puede ser menor a 0.',

            'observaciones.string'             => 'Las observaciones deben ser una cadena de texto.',

            'recojoDomicilio.boolean'          => 'El campo "Recojo a Domicilio" debe ser verdadero o falso.',
            'recojo_direccion.required_if'     => 'La dirección de recojo es requerida cuando el "Recojo a Domicilio" es verdadero.',
            'recojo_ubigeo.required_if'        => 'El ubigeo de recojo es requerido cuando el "Recojo a Domicilio" es verdadero.',
            'recojo_ubigeo.size'              => 'El ubigeo de recojo debe tener exactamente 6 caracteres.',
            'recojo_distrito.required_if'     => 'El distrito de recojo es requerido cuando el "Recojo a Domicilio" es verdadero.',
            'recojo_provincia.required_if'    => 'La provincia de recojo es requerida cuando el "Recojo a Domicilio" es verdadero.',
            'recojo_departamento.required_if' => 'El departamento de recojo es requerido cuando el "Recojo a Domicilio" es verdadero.',
            'recojo_telefono.required_if'     => 'El teléfono de recojo es requerido cuando el "Recojo a Domicilio" es verdadero.',

            'entregaDomicilio.boolean'         => 'El campo "Entrega a Domicilio" debe ser verdadero o falso.',
            'entrega_direccion.required_if'    => 'La dirección de entrega es requerida cuando el "Entrega a Domicilio" es verdadero.',
            'entrega_ubigeo.required_if'       => 'El ubigeo de entrega es requerido cuando el "Entrega a Domicilio" es verdadero.',
            'entrega_ubigeo.size'             => 'El ubigeo de entrega debe tener exactamente 6 caracteres.',
            'entrega_distrito.required_if'    => 'El distrito de entrega es requerido cuando el "Entrega a Domicilio" es verdadero.',
            'entrega_provincia.required_if'   => 'La provincia de entrega es requerida cuando el "Entrega a Domicilio" es verdadero.',
            'entrega_departamento.required_if' => 'El departamento de entrega es requerido cuando el "Entrega a Domicilio" es verdadero.',
            'entrega_telefono.required_if'    => 'El teléfono de entrega es requerido cuando el "Entrega a Domicilio" es verdadero.',

            'items.required'                  => 'Debe agregar al menos un ítem al envío.',
            'items.array'                     => 'Los ítems deben ser un arreglo.',
            'items.min'                        => 'Debe haber al menos un ítem.',
            'items.*.cantidad.required'        => 'La cantidad del ítem es requerida.',
            'items.*.cantidad.integer'         => 'La cantidad debe ser un número entero.',
            'items.*.cantidad.min'             => 'La cantidad no puede ser menor a 1.',
            'items.*.unidad_medida.required'   => 'La unidad de medida del ítem es requerida.',
            'items.*.unidad_medida.in'         => 'La unidad de medida del ítem no es válida.',
            'items.*.descripcion.required'     => 'La descripción del ítem es requerida.',
            'items.*.descripcion.string'       => 'La descripción del ítem debe ser una cadena de texto.',
            'items.*.descripcion.max'          => 'La descripción del ítem no puede exceder los 500 caracteres.',
        ];
    }
}
