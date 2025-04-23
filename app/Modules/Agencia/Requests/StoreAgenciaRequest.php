<?php

namespace Modules\Agencia\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAgenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:10|unique:agencias,codigo',
            'direccion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'ubigeo' => 'nullable|string|max:10',
            'distrito' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'departamento' => 'nullable|string|max:100',
            'estado' => 'boolean',
            'googleMapsUrl' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la agencia es obligatorio.',
            'nombre.string' => 'El nombre de la agencia debe ser un texto válido.',
            'nombre.max' => 'El nombre de la agencia no debe superar los 255 caracteres.',

            'codigo.required' => 'El código de la agencia es obligatorio.',
            'codigo.string' => 'El código de la agencia debe ser un texto válido.',
            'codigo.max' => 'El código de la agencia no debe superar los 10 caracteres.',
            'codigo.unique' => 'El código de la agencia ya está registrado.',

            'direccion.required' => 'La dirección de la agencia es obligatoria.',
            'direccion.string' => 'La dirección de la agencia debe ser un texto válido.',
            'direccion.max' => 'La dirección de la agencia no debe superar los 255 caracteres.',

            'telefono.string' => 'El teléfono de la agencia debe ser un texto válido.',
            'telefono.max' => 'El teléfono de la agencia no debe superar los 20 caracteres.',

            'ubigeo.string' => 'El ubigeo de la agencia debe ser un texto válido.',
            'ubigeo.max' => 'El ubigeo de la agencia no debe superar los 10 caracteres.',

            'distrito.string' => 'El distrito de la agencia debe ser un texto válido.',
            'distrito.max' => 'El distrito de la agencia no debe superar los 100 caracteres.',

            'provincia.string' => 'La provincia de la agencia debe ser un texto válido.',
            'provincia.max' => 'La provincia de la agencia no debe superar los 100 caracteres.',

            'departamento.string' => 'El departamento de la agencia debe ser un texto válido.',
            'departamento.max' => 'El departamento de la agencia no debe superar los 100 caracteres.',

            'estado.boolean' => 'El estado de la agencia debe ser un valor booleano.',

            'googleMapsUrl.string' => 'La URL de Google Maps debe ser un texto válido.',
            'googleMapsUrl.max' => 'La URL de Google Maps no debe superar los 255 caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'Nombre',
            'codigo' => 'Código',
            'direccion' => 'Dirección',
            'telefono' => 'Teléfono',
            'ubigeo' => 'Ubigeo',
            'distrito' => 'Distrito',
            'provincia' => 'Provincia',
            'departamento' => 'Departamento',
            'estado' => 'Estado',
            'googleMapsUrl' => 'URL de Google Maps',
        ];
    }
}
