<?php

namespace Modules\SeguimientoEnvio\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrarSeguimientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'estado'      => 'required|in:registrado,en_recojo,origen,asignado,en_ruta,destino,entregado,cancelado',
            'descripcion' => 'nullable|string|max:255',
        ];
    }
}
