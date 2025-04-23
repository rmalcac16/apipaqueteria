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
        $vehiculo = $this->route('vehiculo');
        $id = $vehiculo instanceof \App\Models\Vehiculo ? $vehiculo->id : $vehiculo;

        return [
            'tipo' => ['required', Rule::in(['vehiculo', 'carreta'])],
            'placa' => [
                'required',
                'string',
                'max:15',
                Rule::unique('vehiculos', 'placa')->ignore($id),
            ],
            'tuc' => ['required', 'string', 'max:50', Rule::unique('vehiculos', 'tuc')->ignore($id)],
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
}
