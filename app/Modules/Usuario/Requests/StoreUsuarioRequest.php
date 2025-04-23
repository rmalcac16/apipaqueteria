<?php

namespace Modules\Usuario\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255|unique:users,name',
            'numero_documento'  => 'required|string|max:20|unique:users,numero_documento',
            'nombreCompleto'    => 'required|string|max:255|unique:users,nombreCompleto',
            'email'             => 'required|email|unique:users,email',
            'rol'               => ['required', Rule::in(['admin', 'agente', 'conductor'])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'             => 'El :attribute es obligatorio.',
            'name.string'               => 'El :attribute debe ser una cadena de texto.',
            'name.max'                  => 'El :attribute no debe tener más de :max caracteres.',
            'name.unique'               => 'El :attribute ya está registrado.',

            'numero_documento.required' => 'El :attribute es obligatorio.',
            'numero_documento.string'   => 'El :attribute debe ser una cadena de texto.',
            'numero_documento.max'      => 'El :attribute no debe tener más de :max caracteres.',
            'numero_documento.unique'   => 'El :attribute ya está registrado.',

            'nombreCompleto.required'   => 'El :attribute es obligatorio.',
            'nombreCompleto.string'     => 'El :attribute debe ser una cadena de texto.',
            'nombreCompleto.max'        => 'El :attribute no debe tener más de :max caracteres.',
            'nombreCompleto.unique'     => 'El :attribute ya está registrado.',

            'email.required'            => 'El :attribute es obligatorio.',
            'email.email'               => 'El :attribute debe ser un correo electrónico válido.',
            'email.unique'              => 'El :attribute ya está registrado.',

            'rol.required'              => 'El :attribute es obligatorio.',
            'rol.in'                    => 'El :attribute seleccionado no es válido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'             => 'nombre de usuario',
            'numero_documento' => 'número de documento',
            'nombreCompleto'   => 'nombre completo',
            'email'            => 'correo electrónico',
            'rol'              => 'rol',
        ];
    }
}
