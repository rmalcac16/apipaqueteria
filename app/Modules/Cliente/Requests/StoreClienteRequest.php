<?php

namespace Modules\Cliente\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para almacenar un cliente.
     */
    public function rules(): array
    {
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
                'unique:clientes,numeroDocumento',
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
                'nullable',
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
            'tipoDocumento.required' => 'El tipo de documento es obligatorio.',
            'tipoDocumento.string'   => 'El tipo de documento debe ser una cadena de texto.',
            'tipoDocumento.in'       => 'El tipo de documento seleccionado no es válido.',

            'numeroDocumento.required' => 'El número de documento es obligatorio.',
            'numeroDocumento.string'   => 'El número de documento debe ser una cadena de texto.',
            'numeroDocumento.unique'   => 'El número de documento ya está registrado.',
            'numeroDocumento.max'      => 'El número de documento no debe superar los 30 caracteres.',

            'nombreCompleto.required' => 'El nombre completo es obligatorio.',
            'nombreCompleto.string'   => 'El nombre completo debe ser una cadena de texto.',
            'nombreCompleto.max'      => 'El nombre completo no debe superar los 255 caracteres.',

            'direccion.string'        => 'La dirección debe ser una cadena de texto.',
            'direccion.max'           => 'La dirección no debe superar los 255 caracteres.',

            'telefono.string'         => 'El teléfono debe ser una cadena de texto.',
            'telefono.regex'          => 'El formato del teléfono no es válido.',
            'telefono.max'            => 'El teléfono no debe superar los 20 caracteres.',

            'correo.email'            => 'El correo electrónico no es válido.',
            'correo.max'              => 'El correo electrónico no debe superar los 100 caracteres.',

            'frecuente.boolean'       => 'El campo cliente frecuente debe ser verdadero o falso.',

            'observaciones.string'    => 'Las observaciones deben ser una cadena de texto.',
        ];
    }

    /**
     * Atributos personalizados para los mensajes de error.
     */
    public function attributes(): array
    {
        return [
            'tipoDocumento'      => 'tipo de documento',
            'numeroDocumento'    => 'número de documento',
            'nombreCompleto'     => 'nombre completo',
            'direccion'          => 'dirección',
            'telefono'           => 'teléfono',
            'correo'             => 'correo electrónico',
            'frecuente'          => 'cliente frecuente',
            'observaciones'      => 'observaciones',
        ];
    }
}
