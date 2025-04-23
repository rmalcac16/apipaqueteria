<?php

namespace Modules\Vehiculo\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Vehiculo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Vehiculo\Requests\StoreVehiculoRequest;
use Modules\Vehiculo\Requests\UpdateVehiculoRequest;
use Modules\Vehiculo\Services\VehiculoService;

class VehiculoController extends Controller
{
    public function __construct(protected VehiculoService $service) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json($this->service->all($request->all()));
    }

    public function show($id): JsonResponse
    {
        return response()->json($this->service->find($id));
    }

    public function store(StoreVehiculoRequest $request): JsonResponse
    {
        $vehiculo = $this->service->create($request->validated());
        return response()->json($vehiculo, 201);
    }

    public function update(UpdateVehiculoRequest $request, Vehiculo $vehiculo): JsonResponse
    {
        $updated = $this->service->update($vehiculo, $request->validated());
        return response()->json($updated);
    }

    public function destroy(Vehiculo $vehiculo): JsonResponse
    {
        $this->service->delete($vehiculo);
        return response()->json(['message' => 'Vehículo eliminado']);
    }
}
