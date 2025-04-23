<?php

namespace Modules\Pago\Requests;

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
            'forma_pago'         => ['required', Rule::in(['efectivo', 'transferencia', 'deposito'])],
            'medio_pago'         => ['nullable', Rule::in(['yape', 'plin', 'bcp', 'interbank', 'bbva', 'otros'])],
            'numero_transaccion' => ['nullable', 'string', 'max:50'],
            'fecha_pago'         => ['nullable', 'date'],
            'realizado_por'      => ['nullable', 'exists:clientes,id'],
            'cobrado_por'        => ['nullable', 'exists:users,id'],
            'agencia_id'         => ['nullable', 'exists:agencias,id'],
            'observaciones'      => ['nullable', 'string', 'max:255'],

            'comprobante' => ['nullable', 'array'],
            'comprobante.tipo' => ['required_with:comprobante', Rule::in(['01', '03'])],
            'comprobante.forma_pago' => ['required_with:comprobante', Rule::in(['contado', 'credito'])],
            'comprobante.cuotas' => ['nullable', 'array'],
            'comprobante.cuotas.*.monto' => ['required_with:comprobante.cuotas', 'numeric', 'min:0.01'],
            'comprobante.cuotas.*.fecha_vencimiento' => ['required_with:comprobante.cuotas', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'envio_id.required'        => 'Debe especificar el envío.',
            'envio_id.exists'          => 'El envío seleccionado no existe.',

            'monto.required'           => 'El monto es obligatorio.',
            'monto.numeric'            => 'El monto debe ser numérico.',
            'monto.min'                => 'El monto debe ser mayor a 0.',

            'forma_pago.required'      => 'La forma de pago es obligatoria.',
            'forma_pago.in'            => 'La forma de pago no es válida.',

            'medio_pago.in'            => 'El medio de pago no es válido.',
            'numero_transaccion.max'   => 'La transacción no debe exceder 50 caracteres.',
            'fecha_pago.date'          => 'La fecha de pago no es válida.',

            'realizado_por.exists'     => 'El cliente seleccionado no existe.',
            'cobrado_por.exists'       => 'El usuario cobrador no existe.',
            'agencia_id.exists'        => 'La agencia seleccionada no existe.',

            'observaciones.max'        => 'Las observaciones no deben exceder 255 caracteres.',


        ];
    }

    public function attributes(): array
    {
        return [
            'envio_id'           => 'envío',
            'monto'              => 'monto',
            'forma_pago'         => 'forma de pago',
            'medio_pago'         => 'medio de pago',
            'numero_transaccion' => 'número de transacción',
            'fecha_pago'         => 'fecha de pago',
            'realizado_por'      => 'cliente',
            'cobrado_por'        => 'usuario que registró el pago',
            'agencia_id'         => 'agencia',
            'observaciones'      => 'observaciones',
        ];
    }
}
