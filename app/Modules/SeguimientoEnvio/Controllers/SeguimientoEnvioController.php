<?php

namespace Modules\SeguimientoEnvio\Controllers;

use App\Http\Controllers\Controller;
use Modules\SeguimientoEnvio\Requests\RegistrarSeguimientoRequest;
use Modules\SeguimientoEnvio\Services\SeguimientoEnvioService;
use App\Models\Envio;
use Illuminate\Support\Facades\Auth;

class SeguimientoEnvioController extends Controller
{
    public function store(
        RegistrarSeguimientoRequest $request,
        SeguimientoEnvioService $service,
        Envio $envio
    ) {
        $seguimiento = $service->registrar(
            $envio,
            $request->estado,
            $request->descripcion,
            Auth::id()
        );

        $envio->estado = $request->estado;
        $envio->save();

        return response()->json([
            'message' => 'Seguimiento actualizado correctamente.',
            'seguimiento' => $seguimiento->append('estado_legible')
        ]);
    }

    public function index($envioId, SeguimientoEnvioService $service)
    {
        return response()->json($service->obtenerPorEnvio($envioId));
    }
}
