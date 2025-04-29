<?php

namespace Modules\PagoEnvio\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\PagoEnvio\Requests\StorePagoEnvioRequest;
use Modules\PagoEnvio\Requests\UpdatePagoEnvioRequest;
use Modules\PagoEnvio\Services\PagoEnvioService;

class PagoEnvioController extends Controller
{
    protected PagoEnvioService $service;

    public function __construct(PagoEnvioService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        return response()->json($this->service->getAll());
    }

    public function store(StorePagoEnvioRequest $request): JsonResponse
    {
        return response()->json($this->service->create($request->validated()), 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->service->find($id));
    }

    public function update(UpdatePagoEnvioRequest $request, int $id): JsonResponse
    {
        return response()->json($this->service->update($id, $request->validated()));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(null, 204);
    }
}
