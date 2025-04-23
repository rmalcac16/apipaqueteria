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
            'envio_id' => [
                'required',
                'exists:envios,id',
                Rule::unique('pago_envios', 'envio_id')->ignore($this->route('pago_envio')),
            ],
            'monto' => 'required|numeric|min:0.01',
            'forma_pago' => 'required|in:efectivo,transferencia,deposito',
            'medio_pago' => 'nullable|in:yape,plin,bcp,interbank,bbva,otros',
            'numero_transaccion' => 'nullable|string|max:100|required_if:forma_pago,transferencia,deposito',
            'realizado_por' => 'required|exists:clientes,id',
            'observaciones' => 'nullable|string|max:500',
            'tipo_comprobante' => 'nullable|in:01,03',
        ];
    }

    public function messages(): array
    {
        return [
            'envio_id.required' => 'El envio es obligatorio.',
            'envio_id.exists' => 'El envio seleccionado no existe.',
            'envio_id.unique' => 'Ya existe un pago registrado para este envio.',
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.min' => 'El monto debe ser mayor a 0.',
            'forma_pago.required' => 'La forma de pago es obligatoria.',
            'forma_pago.in' => 'La forma de pago seleccionada no es válida.',
            'medio_pago.in' => 'El medio de pago seleccionado no es válido.',
            'numero_transaccion.required_if' => 'El número de transacción es obligatorio cuando la forma de pago es transferencia o deposito.',
            'numero_transaccion.string' => 'El número de transacción debe ser una cadena de texto.',
            'numero_transaccion.max' => 'El número de transacción no puede tener más de 100 caracteres.',
            'realizado_por.required' => 'El cliente que ha realizado el pago es obligatorio.',
            'realizado_por.exists' => 'El cliente seleccionado no existe.',
            'observaciones.string' => 'Las observaciones deben ser una cadena de texto.',
            'observaciones.max' => 'Las observaciones no pueden tener más de 500 caracteres.',
            'tipo_comprobante.in' => 'El tipo de comprobante seleccionado no es válido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'envio_id' => 'envío',
            'monto' => 'monto',
            'forma_pago' => 'forma de pago',
            'medio_pago' => 'medio de pago',
            'numero_transaccion' => 'número de transacción',
            'realizado_por' => 'cliente que realizó el pago',
            'observaciones' => 'observaciones',
            'tipo_comprobante' => 'tipo de comprobante',
        ];
    }
}
