<?php

namespace Modules\PagoEnvio\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePagoEnvioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'monto'              => ['sometimes', 'numeric', 'min:0.1'],
            'estado'             => ['sometimes', Rule::in(['pendiente', 'completado', 'cancelado'])],
            'forma_pago'         => ['sometimes', Rule::in(['efectivo', 'transferencia', 'deposito'])],
            'medio_pago'         => ['nullable', Rule::in(['yape', 'plin', 'bcp', 'interbank', 'bbva', 'otros'])],
            'numero_transaccion' => ['nullable', 'string', 'max:50'],
            'fecha_pago'         => ['nullable', 'date'],
            'observaciones'      => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'monto.numeric'             => 'El monto debe ser numérico.',
            'monto.min'                 => 'El monto debe ser mayor a 0.',

            'estado.in'                 => 'El estado no es válido.',
            'forma_pago.in'             => 'La forma de pago no es válida.',
            'medio_pago.in'             => 'El medio de pago no es válido.',
            'numero_transaccion.max'    => 'La transacción no debe exceder 50 caracteres.',
            'fecha_pago.date'           => 'La fecha de pago no es válida.',
            'observaciones.max'         => 'Las observaciones no deben exceder 255 caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'monto'              => 'monto',
            'estado'             => 'estado',
            'forma_pago'         => 'forma de pago',
            'medio_pago'         => 'medio de pago',
            'numero_transaccion' => 'número de transacción',
            'fecha_pago'         => 'fecha de pago',
            'observaciones'      => 'observaciones',
        ];
    }
}
