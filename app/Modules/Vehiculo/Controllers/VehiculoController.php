<?php

namespace Modules\Vehiculo\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Vehiculo;
use Illuminate\Http\JsonResponse;
use Modules\Vehiculo\Requests\StoreVehiculoRequest;
use Modules\Vehiculo\Requests\UpdateVehiculoRequest;
use Modules\Vehiculo\Services\VehiculoService;
use Illuminate\Database\Eloquent\Collection;

class VehiculoController extends Controller
{
    public function __construct(
        protected VehiculoService $vehiculoService
    ) {}

    public function index(): Collection
    {
        return $this->vehiculoService->getAll();
    }

    public function store(StoreVehiculoRequest $request): Vehiculo
    {
        return $this->vehiculoService->store($request->validated());
    }

    public function show(int $id): Vehiculo
    {
        return $this->vehiculoService->find($id);
    }

    public function update(UpdateVehiculoRequest $request, int $id): Vehiculo
    {
        return $this->vehiculoService->updateById($id, $request->validated());
    }

    public function destroy(int $id): JsonResponse
    {
        $this->vehiculoService->deleteById($id);
        return response()->json(null, 204);
    }
}
