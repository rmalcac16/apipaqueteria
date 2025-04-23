<?php

namespace Modules\Vehiculo\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehiculoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo' => ['required', Rule::in(['vehiculo', 'carreta'])],
            'placa' => [
                'required',
                'string',
                'max:15',
                'unique:vehiculos,placa',
            ],
            'tuc' => 'required|string|max:50|unique:vehiculos,tuc',
            'marca' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'anio' => 'nullable|integer|min:1990|max:' . (date('Y') + 1),
            'capacidad_kg' => 'nullable|numeric|min:0',
            'volumen_m3' => 'nullable|numeric|min:0',
            'estado' => 'boolean',
            'carreta_id' => [
                'nullable',
                'exists:vehiculos,id'
            ]
        ];
    }


    public function messages(): array
    {
        return [
            'tipo.required' => 'El tipo de vehículo es obligatorio.',
            'tipo.in' => 'El tipo de vehículo debe ser "vehiculo" o "carreta".',
            'placa.required' => 'La placa es obligatoria.',
            'placa.string' => 'La placa debe ser una cadena de texto.',
            'placa.max' => 'La placa no puede tener más de 15 caracteres.',
            'placa.unique' => 'Ya existe un vehículo registrado con esta placa.',
            'tuc.required' => 'El TUC es obligatorio.',
            'tuc.string' => 'El TUC debe ser una cadena de texto.',
            'tuc.max' => 'El TUC no puede tener más de 50 caracteres.',
            'tuc.unique' => 'Ya existe un vehículo registrado con este TUC.',
            'marca.string' => 'La marca debe ser una cadena de texto.',
            'marca.max' => 'La marca no puede tener más de 100 caracteres.',
            'modelo.string' => 'El modelo debe ser una cadena de texto.',
            'modelo.max' => 'El modelo no puede tener más de 100 caracteres.',
            'anio.integer' => 'El año debe ser un número entero.',
            'anio.min' => 'El año no puede ser menor a 1990.',
            'anio.max' => 'El año no puede ser mayor al año actual más uno.',
            'capacidad_kg.numeric' => 'La capacidad en kg debe ser un número.',
            'capacidad_kg.min' => 'La capacidad en kg no puede ser menor a 0.',
            'volumen_m3.numeric' => 'El volumen en m3 debe ser un número.',
            'volumen_m3.min' => 'El volumen en m3 no puede ser menor a 0.',
            'estado.boolean' => 'El estado debe ser verdadero o falso.',
            'carreta_id.exists' => 'La carreta seleccionada no existe.',
        ];
    }
}
