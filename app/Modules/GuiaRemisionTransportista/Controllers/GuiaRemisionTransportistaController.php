<?php

namespace Modules\GuiaRemisionTransportista\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Envio;
use Modules\GuiaRemisionTransportista\Requests\StoreGuiaRemisionTransportistaRequest;
use Modules\GuiaRemisionTransportista\Services\GuiaRemisionTransportistaService;

class GuiaRemisionTransportistaController extends Controller
{
    protected $service;

    public function __construct(GuiaRemisionTransportistaService $service)
    {
        $this->service = $service;
    }

    public function store(StoreGuiaRemisionTransportistaRequest $request)
    {
        $envio = Envio::findOrFail($request->input('envio_id'));

        $result = $this->service->crearDesdeEnvio($envio, $request->validated());

        return response()->json([
            'message' => 'Guía de Remisión generada correctamente.',
            'data' => $result['guia'],
            'pdf_urls' => $result['pdf_urls'],
        ]);
    }
}
