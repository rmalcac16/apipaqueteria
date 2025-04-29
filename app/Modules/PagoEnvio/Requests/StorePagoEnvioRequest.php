<?php

namespace Modules\PagoEnvio\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePagoEnvioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'envio_id'           => ['required', 'exists:envios,id'],
            'monto'              => ['required', 'numeric', 'min:0.1'],
            'metodo_pago'        => ['required', Rule::in(['efectivo', 'transferencia', 'deposito', 'billetera_digital', 'otros'])],
            'medio_pago'         => ['nullable', Rule::in([
                'bcp',
                'scotiabank',
                'interbank',
                'bbva',
                'banbif',
                'yape',
                'plin',
                'tunki',
                'agora',
                'otros'
            ])],
            'numero_transaccion' => ['nullable', 'string', 'max:50'],
            'fecha_pago'         => ['nullable', 'date'],
            'realizado_por'      => ['required', 'exists:clientes,id'],
            'cobrado_por'        => ['nullable', 'exists:users,id'],
            'agencia_id'         => ['nullable', 'exists:agencias,id'],
            'observaciones'      => ['nullable', 'string', 'max:255'],

            'comprobante'                    => ['nullable', 'array'],
            'comprobante.tipo'               => ['required_with:comprobante', Rule::in(['01', '03'])],
            'comprobante.forma_pago'         => ['required_with:comprobante', Rule::in(['contado', 'credito'])],
            'comprobante.cuotas'             => ['required_if:comprobante.forma_pago,credito', 'array'],
            'comprobante.cuotas.*.monto'     => ['required_with:comprobante.cuotas', 'numeric', 'min:0.01'],
            'comprobante.cuotas.*.fecha_vencimiento' => ['required_with:comprobante.cuotas', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'envio_id.required' => 'El envío es obligatorio.',
            'envio_id.exists' => 'El envío seleccionado no existe.',

            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.min' => 'El monto debe ser mayor a cero.',

            'metodo_pago.required' => 'Debe seleccionar un método de pago.',
            'metodo_pago.in' => 'El método de pago seleccionado no es válido.',

            'medio_pago.in' => 'El medio de pago seleccionado no es válido.',

            'numero_transaccion.string' => 'El número de transacción debe ser un texto.',
            'numero_transaccion.max' => 'El número de transacción no debe exceder 50 caracteres.',

            'fecha_pago.date' => 'La fecha de pago debe ser una fecha válida.',

            'realizado_por.required' => 'El cliente pagador es obligatorio.',
            'realizado_por.exists' => 'El cliente pagador seleccionado no existe.',
            'cobrado_por.exists' => 'El usuario que cobra no existe.',
            'agencia_id.exists' => 'La agencia seleccionada no existe.',

            'observaciones.string' => 'Las observaciones deben ser un texto.',
            'observaciones.max' => 'Las observaciones no deben exceder 255 caracteres.',

            'comprobante.array' => 'El comprobante debe ser un arreglo.',

            'comprobante.tipo.required_with' => 'Debe seleccionar el tipo de comprobante.',
            'comprobante.tipo.in' => 'El tipo de comprobante seleccionado no es válido.',

            'comprobante.forma_pago.required_with' => 'Debe seleccionar la forma de pago del comprobante.',
            'comprobante.forma_pago.in' => 'La forma de pago del comprobante no es válida.',

            'comprobante.cuotas.required_if' => 'Debe especificar las cuotas si la forma de pago es crédito.',
            'comprobante.cuotas.array' => 'Las cuotas deben ser un arreglo.',

            'comprobante.cuotas.*.monto.required_with' => 'Debe ingresar el monto de cada cuota.',
            'comprobante.cuotas.*.monto.numeric' => 'El monto de la cuota debe ser numérico.',
            'comprobante.cuotas.*.monto.min' => 'El monto de la cuota debe ser mayor a 0.',

            'comprobante.cuotas.*.fecha_vencimiento.required_with' => 'Debe ingresar la fecha de vencimiento de cada cuota.',
            'comprobante.cuotas.*.fecha_vencimiento.date' => 'La fecha de vencimiento debe ser una fecha válida.',
        ];
    }

    public function attributes(): array
    {
        return [
            'envio_id' => 'envío',
            'monto' => 'monto',
            'metodo_pago' => 'método de pago',
            'medio_pago' => 'medio de pago',
            'numero_transaccion' => 'número de transacción',
            'fecha_pago' => 'fecha de pago',
            'realizado_por' => 'cliente pagador',
            'cobrado_por' => 'usuario que cobra',
            'agencia_id' => 'agencia',
            'observaciones' => 'observaciones',

            'comprobante' => 'comprobante',
            'comprobante.tipo' => 'tipo de comprobante',
            'comprobante.forma_pago' => 'forma de pago del comprobante',
            'comprobante.cuotas' => 'cuotas',
            'comprobante.cuotas.*.monto' => 'monto de la cuota',
            'comprobante.cuotas.*.fecha_vencimiento' => 'fecha de vencimiento de la cuota',
        ];
    }
}
