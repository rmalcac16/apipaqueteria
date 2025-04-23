<?php

namespace Modules\Viaje\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Viaje\Services\ViajeService;
use Modules\Viaje\Requests\StoreViajeRequest;
use Modules\Viaje\Requests\UpdateViajeRequest;
use App\Models\Viaje;
use Illuminate\Database\Eloquent\Collection;

class ViajeController extends Controller
{
    protected ViajeService $viajeService;

    public function __construct(ViajeService $viajeService)
    {
        $this->viajeService = $viajeService;
    }

    /**
     * Listar todos los viajes.
     */
    public function index(): Collection
    {
        return $this->viajeService->getAll();
    }

    /**
     * Registrar un nuevo viaje.
     */
    public function store(StoreViajeRequest $request): Viaje
    {
        return $this->viajeService->store($request->validated());
    }

    /**
     * Mostrar un viaje específico.
     */
    public function show(int $id): Viaje
    {
        return $this->viajeService->find($id);
    }

    /**
     * Actualizar un viaje existente.
     */
    public function update(UpdateViajeRequest $request, int $id): Viaje
    {
        return $this->viajeService->updateById($id, $request->validated());
    }

    /**
     * Eliminar un viaje.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->viajeService->deleteById($id);
        return response()->json(null, 204);
    }
}
