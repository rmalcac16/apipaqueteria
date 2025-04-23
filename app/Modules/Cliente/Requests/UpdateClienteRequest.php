<?php

namespace Modules\Cliente\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClienteRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para actualizar un cliente.
     */
    public function rules(): array
    {
        $clienteId = $this->route('cliente')->id ?? null;

        return [
            'tipoDocumento' => [
                'required',
                'string',
                'in:0,1,2,3,4,5,6,A,B,C,D,E,F',
            ],
            'numeroDocumento' => [
                'required',
                'string',
                'max:30',
                Rule::unique('clientes', 'numeroDocumento')->ignore($clienteId),
            ],
            'nombreCompleto' => [
                'required',
                'string',
                'max:255',
            ],
            'direccion' => [
                'nullable',
                'string',
                'max:255',
            ],
            'telefono' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9+\s\-()]+$/',
            ],
            'correo' => [
                'nullable',
                'email',
                'max:100',
            ],
            'frecuente' => [
                'boolean',
            ],
            'observaciones' => [
                'nullable',
                'string',
            ],
        ];
    }

    /**
     * Mensajes personalizados para errores de validación.
     */
    public function messages(): array
    {
        return [
            'tipoDocumento.required' => 'El :attribute es obligatorio.',
            'tipoDocumento.string'   => 'El :attribute debe ser una cadena de texto.',
            'tipoDocumento.in'       => 'El :attribute seleccionado no es válido.',

            'numeroDocumento.required' => 'El :attribute es obligatorio.',
            'numeroDocumento.string'   => 'El :attribute debe ser una cadena de texto.',
            'numeroDocumento.unique'   => 'El :attribute ya está registrado.',
            'numeroDocumento.max'      => 'El :attribute no debe superar los 30 caracteres.',

            'nombreCompleto.required' => 'El :attribute es obligatorio.',
            'nombreCompleto.max'      => 'El :attribute no debe superar los 255 caracteres.',

            'direccion.max'           => 'La :attribute no debe superar los 255 caracteres.',

            'telefono.required'       => 'El :attribute es obligatorio.',
            'telefono.regex'          => 'El formato del :attribute no es válido.',
            'telefono.max'            => 'El :attribute no debe superar los 20 caracteres.',

            'correo.email'            => 'El :attribute electrónico no es válido.',
            'correo.max'              => 'El :attribute electrónico no debe superar los 100 caracteres.',

            'frecuente.boolean'       => 'El campo :attribute debe ser verdadero o falso.',

            'observaciones.string'    => 'Las :attribute deben ser una cadena de texto.',
        ];
    }

    /**
     * Atributos personalizados para mostrar en los mensajes de error.
     */
    public function attributes(): array
    {
        return [
            'tipoDocumento'     => 'tipo de documento',
            'numeroDocumento'   => 'número de documento',
            'nombreCompleto'    => 'nombre completo',
            'direccion'         => 'dirección',
            'telefono'          => 'teléfono',
            'correo'            => 'correo electrónico',
            'frecuente'         => 'cliente frecuente',
            'observaciones'     => 'observaciones',
        ];
    }
}
