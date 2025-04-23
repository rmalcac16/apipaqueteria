<?php

namespace Modules\Envio\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEnvioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('envio');

        $rules = (new StoreEnvioRequest)->rules();

        $rules['numeroOrden'] = ['required', 'numeric', Rule::unique('envios', 'numeroOrden')->ignore($id)];
        $rules['codigo']      = ['required', 'string', 'max:50', Rule::unique('envios', 'codigo')->ignore($id)];

        return $rules;
    }

    public function attributes(): array
    {
        return (new StoreEnvioRequest)->attributes();
    }
}
