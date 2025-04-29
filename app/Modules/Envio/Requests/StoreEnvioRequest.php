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
            'fechaTraslado'          => 'required|date|after_or_equal:today',

            'remitente_id'           => 'required|exists:clientes,id',
            'destinatario_id'        => 'required|exists:clientes,id',
            'pagador_id'             => 'required|exists:clientes,id',

            'viaje_id'               => 'required|exists:viajes,id',

            'agencia_origen_id'      => 'required|exists:agencias,id',
            'agencia_destino_id'     => 'required|exists:agencias,id|different:agencia_origen_id',

            'pesoTotal'              => 'nullable|numeric|min:0',
            'unidadMedida'           => ['required', Rule::in(['KGM', 'TN', 'LBR', 'OZ'])],
            'valorDeclarado'         => 'nullable|numeric|min:0',
            'esFragil'               => 'boolean',
            'esPeligroso'            => 'boolean',
            'costoEnvio'             => 'required|numeric|min:0',
            'formaPago'              => ['required', Rule::in(['contado', 'contraentrega', 'credito'])],
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

    public function messages(): array
    {
        return [
            'fechaTraslado.required' => 'La fecha de traslado es obligatoria.',
            'fechaTraslado.date' => 'La fecha de traslado debe ser una fecha válida.',
            'fechaTraslado.after_or_equal' => 'La fecha de traslado no puede ser anterior a hoy.',

            'remitente_id.required' => 'El remitente es obligatorio.',
            'remitente_id.exists' => 'El remitente seleccionado no existe.',

            'destinatario_id.required' => 'El destinatario es obligatorio.',
            'destinatario_id.exists' => 'El destinatario seleccionado no existe.',

            'pagador_id.required' => 'El pagador es obligatorio.',
            'pagador_id.exists' => 'El pagador seleccionado no existe.',

            'viaje_id.required' => 'El viaje es obligatorio.',
            'viaje_id.exists' => 'El viaje seleccionado no existe.',

            'agencia_origen_id.required' => 'La agencia de origen es obligatoria.',
            'agencia_origen_id.exists' => 'La agencia de origen seleccionada no existe.',

            'agencia_destino_id.required' => 'La agencia de destino es obligatoria.',
            'agencia_destino_id.exists' => 'La agencia de destino seleccionada no existe.',
            'agencia_destino_id.different' => 'La agencia de destino debe ser diferente a la agencia de origen.',

            'pesoTotal.numeric' => 'El peso total debe ser un número.',
            'pesoTotal.min' => 'El peso total no puede ser negativo.',

            'unidadMedida.required' => 'La unidad de medida es obligatoria.',
            'unidadMedida.in' => 'La unidad de medida seleccionada no es válida.',

            'valorDeclarado.numeric' => 'El valor declarado debe ser un número.',
            'valorDeclarado.min' => 'El valor declarado no puede ser negativo.',

            'esFragil.boolean' => 'El campo frágil debe ser verdadero o falso.',
            'esPeligroso.boolean' => 'El campo peligroso debe ser verdadero o falso.',

            'costoEnvio.required' => 'El costo de envío es obligatorio.',
            'costoEnvio.numeric' => 'El costo de envío debe ser un número.',
            'costoEnvio.min' => 'El costo de envío no puede ser negativo.',

            'formaPago.required' => 'La forma de pago es obligatoria.',
            'formaPago.in' => 'La forma de pago seleccionada no es válida.',

            'observaciones.string' => 'Las observaciones deben ser un texto.',

            'recojoDomicilio.boolean' => 'El campo de recojo a domicilio debe ser verdadero o falso.',

            'recojo_direccion.required_if' => 'La dirección de recojo es obligatoria cuando se solicita recojo a domicilio.',
            'recojo_direccion.string' => 'La dirección de recojo debe ser un texto.',
            'recojo_direccion.max' => 'La dirección de recojo no puede tener más de 255 caracteres.',

            'recojo_ubigeo.required_if' => 'El ubigeo de recojo es obligatorio cuando se solicita recojo a domicilio.',
            'recojo_ubigeo.string' => 'El ubigeo de recojo debe ser un texto.',
            'recojo_ubigeo.size' => 'El ubigeo de recojo debe tener exactamente 6 caracteres.',

            'recojo_distrito.required_if' => 'El distrito de recojo es obligatorio cuando se solicita recojo a domicilio.',
            'recojo_distrito.string' => 'El distrito de recojo debe ser un texto.',
            'recojo_distrito.max' => 'El distrito de recojo no puede tener más de 255 caracteres.',

            'recojo_provincia.required_if' => 'La provincia de recojo es obligatoria cuando se solicita recojo a domicilio.',
            'recojo_provincia.string' => 'La provincia de recojo debe ser un texto.',
            'recojo_provincia.max' => 'La provincia de recojo no puede tener más de 255 caracteres.',

            'recojo_departamento.required_if' => 'El departamento de recojo es obligatorio cuando se solicita recojo a domicilio.',
            'recojo_departamento.string' => 'El departamento de recojo debe ser un texto.',
            'recojo_departamento.max' => 'El departamento de recojo no puede tener más de 255 caracteres.',

            'recojo_referencia.string' => 'La referencia de recojo debe ser un texto.',
            'recojo_referencia.max' => 'La referencia de recojo no puede tener más de 255 caracteres.',

            'recojo_telefono.required_if' => 'El teléfono de recojo es obligatorio cuando se solicita recojo a domicilio.',
            'recojo_telefono.string' => 'El teléfono de recojo debe ser un texto.',
            'recojo_telefono.max' => 'El teléfono de recojo no puede tener más de 20 caracteres.',

            'entregaDomicilio.boolean' => 'El campo de entrega a domicilio debe ser verdadero o falso.',

            'entrega_direccion.required_if' => 'La dirección de entrega es obligatoria cuando se solicita entrega a domicilio.',
            'entrega_direccion.string' => 'La dirección de entrega debe ser un texto.',
            'entrega_direccion.max' => 'La dirección de entrega no puede tener más de 255 caracteres.',

            'entrega_ubigeo.required_if' => 'El ubigeo de entrega es obligatorio cuando se solicita entrega a domicilio.',
            'entrega_ubigeo.string' => 'El ubigeo de entrega debe ser un texto.',
            'entrega_ubigeo.size' => 'El ubigeo de entrega debe tener exactamente 6 caracteres.',

            'entrega_distrito.required_if' => 'El distrito de entrega es obligatorio cuando se solicita entrega a domicilio.',
            'entrega_distrito.string' => 'El distrito de entrega debe ser un texto.',
            'entrega_distrito.max' => 'El distrito de entrega no puede tener más de 255 caracteres.',

            'entrega_provincia.required_if' => 'La provincia de entrega es obligatoria cuando se solicita entrega a domicilio.',
            'entrega_provincia.string' => 'La provincia de entrega debe ser un texto.',
            'entrega_provincia.max' => 'La provincia de entrega no puede tener más de 255 caracteres.',

            'entrega_departamento.required_if' => 'El departamento de entrega es obligatorio cuando se solicita entrega a domicilio.',
            'entrega_departamento.string' => 'El departamento de entrega debe ser un texto.',
            'entrega_departamento.max' => 'El departamento de entrega no puede tener más de 255 caracteres.',

            'entrega_referencia.string' => 'La referencia de entrega debe ser un texto.',
            'entrega_referencia.max' => 'La referencia de entrega no puede tener más de 255 caracteres.',

            'entrega_telefono.required_if' => 'El teléfono de entrega es obligatorio cuando se solicita entrega a domicilio.',
            'entrega_telefono.string' => 'El teléfono de entrega debe ser un texto.',
            'entrega_telefono.max' => 'El teléfono de entrega no puede tener más de 20 caracteres.',

            'items.required' => 'Debe agregar al menos un ítem.',
            'items.array' => 'Los ítems deben ser un arreglo.',
            'items.min' => 'Debe agregar al menos un ítem.',

            'items.*.cantidad.required' => 'La cantidad del ítem es obligatoria.',
            'items.*.cantidad.integer' => 'La cantidad del ítem debe ser un número entero.',
            'items.*.cantidad.min' => 'La cantidad del ítem debe ser al menos 1.',

            'items.*.unidad_medida.required' => 'La unidad de medida del ítem es obligatoria.',
            'items.*.unidad_medida.in' => 'La unidad de medida del ítem no es válida.',

            'items.*.codigo.string' => 'El código del ítem debe ser un texto.',
            'items.*.codigo.max' => 'El código del ítem no puede tener más de 50 caracteres.',

            'items.*.descripcion.required' => 'La descripción del ítem es obligatoria.',
            'items.*.descripcion.string' => 'La descripción del ítem debe ser un texto.',
            'items.*.descripcion.max' => 'La descripción del ítem no puede tener más de 500 caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'fechaTraslado' => 'fecha de traslado',
            'remitente_id' => 'remitente',
            'destinatario_id' => 'destinatario',
            'pagador_id' => 'pagador',
            'viaje_id' => 'viaje',
            'agencia_origen_id' => 'agencia de origen',
            'agencia_destino_id' => 'agencia de destino',
            'pesoTotal' => 'peso total',
            'unidadMedida' => 'unidad de medida',
            'valorDeclarado' => 'valor declarado',
            'esFragil' => 'fragilidad',
            'esPeligroso' => 'peligrosidad',
            'costoEnvio' => 'costo de envío',
            'formaPago' => 'forma de pago',
            'observaciones' => 'observaciones',

            'recojoDomicilio' => 'recojo a domicilio',
            'recojo_direccion' => 'dirección de recojo',
            'recojo_ubigeo' => 'ubigeo de recojo',
            'recojo_distrito' => 'distrito de recojo',
            'recojo_provincia' => 'provincia de recojo',
            'recojo_departamento' => 'departamento de recojo',
            'recojo_referencia' => 'referencia de recojo',
            'recojo_telefono' => 'teléfono de recojo',

            'entregaDomicilio' => 'entrega a domicilio',
            'entrega_direccion' => 'dirección de entrega',
            'entrega_ubigeo' => 'ubigeo de entrega',
            'entrega_distrito' => 'distrito de entrega',
            'entrega_provincia' => 'provincia de entrega',
            'entrega_departamento' => 'departamento de entrega',
            'entrega_referencia' => 'referencia de entrega',
            'entrega_telefono' => 'teléfono de entrega',

            'items' => 'ítems',
            'items.*.cantidad' => 'cantidad del ítem',
            'items.*.unidad_medida' => 'unidad de medida del ítem',
            'items.*.codigo' => 'código del ítem',
            'items.*.descripcion' => 'descripción del ítem',
        ];
    }
}
