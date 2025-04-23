<?php

namespace Modules\SeguimientoEnvio\Services;

use App\Models\SeguimientoEnvio;
use App\Models\Envio;

class SeguimientoEnvioService
{
    public function registrar(Envio $envio, string $estado, ?string $descripcion = null, ?int $usuarioId = null): SeguimientoEnvio
    {
        return SeguimientoEnvio::create([
            'envio_id'   => $envio->id,
            'estado'     => $estado,
            'descripcion' => $descripcion,
            'usuario_id' => $usuarioId,
        ]);
    }


    public function obtenerPorEnvio(int $envioId)
    {
        return SeguimientoEnvio::where('envio_id', $envioId)
            ->orderBy('created_at')
            ->get()
            ->each->append('estado_legible');
    }
}
