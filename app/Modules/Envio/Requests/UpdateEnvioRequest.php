<?php

namespace Modules\Envio\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnvioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [


            // Actores del envío
            'remitente_id' => 'required|exists:clientes,id',
            'destinatario_id' => 'required|exists:clientes,id',
            'tercero_pagador_id' => 'nullable|exists:clientes,id',

            // Origen
            'agencia_origen_id' => 'required|exists:agencias,id',
            'direccion_exacta_origen' => 'required|string|max:255',
            'distrito_origen' => 'required|string|max:100',
            'provincia_origen' => 'required|string|max:100',
            'departamento_origen' => 'required|string|max:100',
            'ubigeo_origen' => 'required|string|max:10',

            // Destino
            'agencia_destino_id' => 'required|exists:agencias,id',
            'direccion_exacta_destino' => 'required|string|max:255',
            'distrito_destino' => 'required|string|max:100',
            'provincia_destino' => 'required|string|max:100',
            'departamento_destino' => 'required|string|max:100',
            'ubigeo_destino' => 'required|string|max:10',

            // Información adicional
            'pagador_flete' => 'required|in:remitente,destinatario,tercero',
            'descripcion' => 'nullable|string|max:1000',
            'peso_kg' => 'nullable|numeric|min:0',
            'volumen_m3' => 'nullable|numeric|min:0',
            'es_fragil' => 'required|boolean',
            'requiere_recogido' => 'required|boolean',
            'entrega_domicilio' => 'required|boolean',

            // Fecha
            'fecha_envio' => 'nullable|date',
        ];
    }
}
