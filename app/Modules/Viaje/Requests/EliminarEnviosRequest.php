<?php

namespace Modules\Viaje\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EliminarEnviosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'envios' => 'required|array',
            'envios.*' => 'integer|exists:envios,id'
        ];
    }
}
