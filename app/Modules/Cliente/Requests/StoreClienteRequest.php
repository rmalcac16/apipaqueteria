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
            'tipo_documento' => [
                'required',
                'string',
                'in:0,1,2,3,4,5,6,A,B,C,D,E,F',
            ],
            'documento' => [
                'required',
                'string',
                'max:30',
                'unique:clientes,documento',
            ],
            'nombre_completo' => [
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
            'tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'tipo_documento.string'   => 'El tipo de documento debe ser una cadena de texto.',
            'tipo_documento.in'       => 'El tipo de documento seleccionado no es válido.',

            'documento.required'      => 'El número de documento es obligatorio.',
            'documento.string'        => 'El número de documento debe ser una cadena de texto.',
            'documento.unique'        => 'El número de documento ya está registrado.',
            'documento.max'           => 'El número de documento no debe superar los 30 caracteres.',

            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'nombre_completo.max'      => 'El nombre completo no debe superar los 255 caracteres.',

            'direccion.max'           => 'La dirección no debe superar los 255 caracteres.',

            'telefono.required'       => 'El teléfono es obligatorio.',
            'telefono.regex'          => 'El formato del teléfono no es válido.',
            'telefono.max'            => 'El teléfono no debe superar los 20 caracteres.',

            'correo.email'            => 'El correo electrónico no es válido.',
            'correo.max'              => 'El correo electrónico no debe superar los 100 caracteres.',

            'frecuente.boolean'       => 'El campo frecuente debe ser verdadero o falso.',

            'observaciones.string'    => 'Las observaciones deben ser una cadena de texto.',
        ];
    }

    /**
     * Atributos personalizados para mostrar en los mensajes de error.
     */
    public function attributes(): array
    {
        return [
            'tipo_documento'  => 'tipo de documento',
            'documento'       => 'número de documento',
            'nombre_completo' => 'nombre completo',
            'direccion'       => 'dirección',
            'telefono'        => 'teléfono',
            'correo'          => 'correo electrónico',
            'frecuente'       => 'cliente frecuente',
            'observaciones'   => 'observaciones',
        ];
    }
}
