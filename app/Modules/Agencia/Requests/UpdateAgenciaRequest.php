<?php

namespace Modules\Agencia\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAgenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $agencia = $this->route('agencia');

        return [
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:10|unique:agencias,codigo,' . $agencia->id,
            'direccion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'ubigeo' => 'nullable|string|max:10',
            'distrito' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'departamento' => 'nullable|string|max:100',
            'estado' => 'nullable|boolean',
            'googleMapsUrl' => 'nullable|string|max:255',
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la agencia es obligatorio.',
            'nombre.string' => 'El nombre de la agencia debe ser una cadena de texto.',
            'nombre.max' => 'El nombre de la agencia no debe superar los 255 caracteres.',
            'codigo.required' => 'El código de la agencia es obligatorio.',
            'codigo.string' => 'El código de la agencia debe ser una cadena de texto.',
            'codigo.max' => 'El código de la agencia no debe superar los 10 caracteres.',
            'codigo.unique' => 'Este código de agencia ya está registrado.',
            'direccion.required' => 'La dirección de la agencia es obligatoria.',
            'direccion.string' => 'La dirección debe ser una cadena de texto.',
            'direccion.max' => 'La dirección no debe superar los 255 caracteres.',
            'telefono.string' => 'El teléfono debe ser una cadena de texto.',
            'telefono.max' => 'El teléfono no debe superar los 20 caracteres.',
            'ubigeo.string' => 'El ubigeo debe ser una cadena de texto.',
            'ubigeo.max' => 'El ubigeo no debe superar los 10 caracteres.',
            'distrito.string' => 'El distrito debe ser una cadena de texto.',
            'distrito.max' => 'El distrito no debe superar los 100 caracteres.',
            'provincia.string' => 'La provincia debe ser una cadena de texto.',
            'provincia.max' => 'La provincia no debe superar los 100 caracteres.',
            'departamento.string' => 'El departamento debe ser una cadena de texto.',
            'departamento.max' => 'El departamento no debe superar los 100 caracteres.',
            'estado.boolean' => 'El estado debe ser un valor booleano.',
            'googleMapsUrl.string' => 'La URL de Google Maps debe ser una cadena de texto.',
            'googleMapsUrl.max' => 'La URL de Google Maps no debe superar los 255 caracteres.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser de tipo jpg, jpeg, png o webp.',
            'image.max' => 'La imagen no debe superar los 2 MB.',

        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'Nombre de la agencia',
            'codigo' => 'Código de la agencia',
            'direccion' => 'Dirección de la agencia',
            'telefono' => 'Teléfono de la agencia',
            'ubigeo' => 'Ubigeo de la agencia',
            'distrito' => 'Distrito de la agencia',
            'provincia' => 'Provincia de la agencia',
            'departamento' => 'Departamento de la agencia',
            'estado' => 'Estado de la agencia',
            'googleMapsUrl' => 'URL de Google Maps de la agencia',
            'image' => 'URL de la imagen de la agencia',
        ];
    }
}
