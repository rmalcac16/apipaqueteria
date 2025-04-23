<?php

namespace Modules\Agencia\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agencia;
use Modules\Agencia\Requests\StoreAgenciaRequest;
use Modules\Agencia\Requests\UpdateAgenciaRequest;
use Modules\Agencia\Services\AgenciaService;
use Illuminate\Http\JsonResponse;

class AgenciaController extends Controller
{
    public function __construct(protected AgenciaService $service) {}

    public function index(): JsonResponse
    {
        return response()->json($this->service->all());
    }

    public function show($id): JsonResponse
    {
        return response()->json($this->service->find($id));
    }

    public function store(StoreAgenciaRequest $request): JsonResponse
    {
        $agencia = $this->service->create($request->validated());
        return response()->json($agencia, 201);
    }

    public function update(UpdateAgenciaRequest $request, Agencia $agencia): JsonResponse
    {
        $updated = $this->service->update($agencia, $request->validated());
        return response()->json($updated);
    }

    public function destroy(Agencia $agencia): JsonResponse
    {
        $this->service->delete($agencia);
        return response()->json(['message' => 'Agencia eliminada']);
    }
}
