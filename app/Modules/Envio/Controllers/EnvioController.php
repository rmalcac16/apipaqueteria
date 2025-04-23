<?php

namespace Modules\Envio\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Envio;
use Illuminate\Http\JsonResponse;
use Modules\Envio\Requests\StoreEnvioRequest;
use Modules\Envio\Requests\UpdateEnvioRequest;
use Modules\Envio\Services\EnvioService;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class EnvioController extends Controller
{
    public function __construct(protected EnvioService $service) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json($this->service->all($request));
    }

    public function show($id): JsonResponse
    {
        return response()->json($this->service->find($id));
    }

    public function store(StoreEnvioRequest $request): JsonResponse
    {
        $envio = $this->service->create($request->validated());

        return response()->json($envio, 201);
    }

    public function update(UpdateEnvioRequest $request, Envio $envio): JsonResponse
    {

        $updated = $this->service->update($envio, $request->validated());
        return response()->json($updated);
    }

    public function destroy(Envio $envio): JsonResponse
    {
        $this->service->delete($envio);
        return response()->json(['message' => 'Envío eliminado']);
    }


    public function findByCodigo(Request $request): JsonResponse
    {
        $envio = $this->service->findByCodigo($request->codigo);
        if (!$envio) {
            return response()->json(['message' => 'Envío no encontrado'], 404);
        }
        return response()->json($envio);
    }


    // Cancelar Envío

    public function cancelar(Envio $envio): JsonResponse
    {
        $this->service->cancelar($envio);
        return response()->json(['message' => 'Envío cancelado']);
    }


    public function trackingEnvio(Request $request): JsonResponse
    {
        $envio = $this->service->trackingEnvio($request->codigo);
        if (!$envio) {
            return response()->json(['message' => 'Envío no encontrado'], 404);
        }
        return response()->json($envio);
    }
}
