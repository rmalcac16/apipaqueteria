<?php

namespace Modules\Vehiculo\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehiculoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('vehiculo');

        return [
            'tipo'          => ['required', Rule::in(['tractocamion', 'remolque', 'semirremolque'])],
            'placa'         => ['required', 'string', 'max:10', Rule::unique('vehiculos', 'placa')->ignore($id)],
            'tuc'           => ['required', 'string', 'max:30', Rule::unique('vehiculos', 'tuc')->ignore($id)],
            'marca'         => 'nullable|string|max:50',
            'modelo'        => 'nullable|string|max:50',
            'anio'          => 'nullable|integer|digits:4|min:1900|max:' . (date('Y') + 1),
            'capacidadKg'   => 'nullable|numeric|min:0',
            'volumenM3'     => 'nullable|numeric|min:0',
            'estado'        => 'boolean',
            'acopladoA_id'  => 'nullable|exists:vehiculos,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'tipo'         => 'tipo de vehículo',
            'placa'        => 'placa',
            'tuc'          => 'TUC',
            'marca'        => 'marca',
            'modelo'       => 'modelo',
            'anio'         => 'año',
            'capacidadKg'  => 'capacidad (kg)',
            'volumenM3'    => 'volumen (m³)',
            'estado'       => 'estado',
            'acopladoA_id' => 'vehículo acoplado',
        ];
    }

    public function messages(): array
    {
        return [
            'tipo.required'         => 'El :attribute es obligatorio.',
            'tipo.in'               => 'El :attribute debe ser tractocamion, remolque o semirremolque.',

            'placa.required'        => 'La :attribute es obligatoria.',
            'placa.unique'          => 'La :attribute ya está registrada.',
            'placa.max'             => 'La :attribute no debe superar :max caracteres.',

            'tuc.required'          => 'El :attribute es obligatorio.',
            'tuc.unique'            => 'El :attribute ya está registrado.',
            'tuc.max'               => 'El :attribute no debe superar :max caracteres.',

            'anio.integer'          => 'El :attribute debe ser un número entero.',
            'anio.digits'           => 'El :attribute debe tener exactamente 4 dígitos.',
            'anio.min'              => 'El :attribute no puede ser menor a :min.',
            'anio.max'              => 'El :attribute no puede ser mayor a :max.',

            'capacidadKg.numeric'   => 'La :attribute debe ser un número.',
            'capacidadKg.min'       => 'La :attribute no puede ser negativa.',

            'volumenM3.numeric'     => 'El :attribute debe ser un número.',
            'volumenM3.min'         => 'El :attribute no puede ser negativo.',

            'acopladoA_id.exists'   => 'El vehículo acoplado seleccionado no existe.',
        ];
    }
}
