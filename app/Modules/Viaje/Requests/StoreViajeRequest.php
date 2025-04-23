<?php

namespace Modules\Viaje\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreViajeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'conductor_id' => 'required|exists:users,id',
            'agencia_origen_id' => 'required|exists:agencias,id|different:agencia_destino_id',
            'agencia_destino_id' => 'required|exists:agencias,id',
            'fecha_salida' => 'required|date|after_or_equal:today',
            'fecha_llegada_estimada' => 'nullable|date|after:fecha_salida',
            'hora_salida_programada' => 'nullable|date_format:H:i',
            'tipo_carga' => 'nullable|string|max:100',
            'viaje_urgente' => 'sometimes|boolean',
            'capacidad_usada' => 'nullable|numeric|min:0|max:100',
            'capacidad_maxima_permitida' => 'nullable|numeric|min:0',
            'tiempo_estimado_viaje' => 'nullable|integer|min:0',
            'observaciones' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'vehiculo_id.required' => 'El vehículo es obligatorio.',
            'vehiculo_id.exists' => 'El vehículo seleccionado no existe.',
            'conductor_id.required' => 'El conductor es obligatorio.',
            'conductor_id.exists' => 'El conductor seleccionado no existe.',
            'agencia_origen_id.required' => 'La agencia de origen es obligatoria.',
            'agencia_origen_id.exists' => 'La agencia de origen no es válida.',
            'agencia_origen_id.different' => 'La agencia de origen debe ser diferente a la de destino.',
            'agencia_destino_id.required' => 'La agencia de destino es obligatoria.',
            'agencia_destino_id.exists' => 'La agencia de destino no es válida.',
            'fecha_salida.required' => 'La fecha de salida es obligatoria.',
            'fecha_salida.date' => 'La fecha de salida debe tener un formato válido.',
            'fecha_salida.after_or_equal' => 'La fecha de salida no puede ser anterior a hoy.',
            'fecha_llegada_estimada.date' => 'La fecha estimada de llegada debe ser válida.',
            'fecha_llegada_estimada.after' => 'La llegada estimada debe ser posterior a la salida.',
            'hora_salida_programada.date_format' => 'La hora de salida debe tener el formato HH:mm.',
            'capacidad_usada.numeric' => 'La capacidad usada debe ser un número.',
            'capacidad_usada.min' => 'La capacidad usada no puede ser menor a 0.',
            'capacidad_usada.max' => 'La capacidad usada no puede ser mayor a 100.',
            'capacidad_maxima_permitida.numeric' => 'La capacidad máxima permitida debe ser un número.',
            'capacidad_maxima_permitida.min' => 'La capacidad máxima debe ser al menos 0.',
            'tiempo_estimado_viaje.integer' => 'El tiempo estimado debe ser un número entero.',
            'tiempo_estimado_viaje.min' => 'El tiempo estimado no puede ser negativo.',
        ];
    }
}
