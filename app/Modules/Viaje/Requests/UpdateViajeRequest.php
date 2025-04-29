<?php

namespace Modules\Viaje\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateViajeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('viaje');

        return [
            'codigo'                  => ['required', 'string', 'max:50', Rule::unique('viajes', 'codigo')->ignore($id)],
            'user_id'                 => 'required|exists:users,id',
            'vehiculo_principal_id'   => 'required|exists:vehiculos,id',
            'vehiculo_secundario_id'  => 'nullable|exists:vehiculos,id',
            'conductor_principal_id'  => 'required|exists:users,id',
            'conductor_secundario_id' => 'nullable|exists:users,id',
            'agencia_origen_id'       => 'required|exists:agencias,id',
            'agencia_destino_id'      => 'required|exists:agencias,id|different:agencia_origen_id',
            'fecha_salida'            => 'required|date|after_or_equal:today',
            'fecha_llegada'          => 'nullable|date|after:fecha_salida',
            'estado'                  => ['required', Rule::in(['programado', 'en_transito', 'finalizado', 'cancelado'])],
        ];
    }

    public function attributes(): array
    {
        return [
            'codigo'                  => 'código',
            'user_id'                 => 'usuario creador',
            'vehiculo_principal_id'   => 'vehículo principal',
            'vehiculo_secundario_id'  => 'vehículo secundario',
            'conductor_principal_id'  => 'conductor principal',
            'conductor_secundario_id' => 'conductor secundario',
            'agencia_origen_id'       => 'agencia de origen',
            'agencia_destino_id'      => 'agencia de destino',
            'fecha_salida'            => 'fecha de salida',
            'fecha_llegada'          => 'fecha de llegada',
            'estado'                  => 'estado',
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required'     => 'El :attribute es obligatorio.',
            'codigo.unique'       => 'El :attribute ya ha sido registrado.',
            'codigo.max'          => 'El :attribute no debe exceder los :max caracteres.',

            'user_id.required'    => 'El :attribute es obligatorio.',
            'user_id.exists'      => 'El :attribute seleccionado no existe.',

            'vehiculo_principal_id.required' => 'El :attribute es obligatorio.',
            'vehiculo_principal_id.exists'   => 'El :attribute seleccionado no existe.',

            'vehiculo_secundario_id.exists'  => 'El :attribute seleccionado no existe.',

            'conductor_principal_id.required' => 'El :attribute es obligatorio.',
            'conductor_principal_id.exists'   => 'El :attribute seleccionado no existe.',

            'conductor_secundario_id.exists'  => 'El :attribute seleccionado no existe.',

            'agencia_origen_id.required' => 'La :attribute es obligatoria.',
            'agencia_origen_id.exists'   => 'La :attribute seleccionada no existe.',

            'agencia_destino_id.required' => 'La :attribute es obligatoria.',
            'agencia_destino_id.exists'   => 'La :attribute seleccionada no existe.',
            'agencia_destino_id.different' => 'La agencia de destino debe ser diferente a la de origen.',

            'fecha_salida.required' => 'La :attribute es obligatoria.',
            'fecha_salida.date'     => 'La :attribute debe ser una fecha válida.',
            'fecha_salida.after_or_equal' => 'La :attribute no puede ser anterior a hoy.',

            'fecha_llegada.date'   => 'La :attribute debe ser una fecha válida.',
            'fecha_llegada.after'  => 'La :attribute debe ser posterior a la fecha de salida.',

            'estado.required' => 'El :attribute es obligatorio.',
            'estado.in'       => 'El :attribute debe ser uno de los siguientes: programado, en_transito, finalizado, cancelado.',
        ];
    }
}
