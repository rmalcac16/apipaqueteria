<?php

namespace Modules\Cliente\Controllers;

use App\Http\Controllers\Controller;
use Modules\Cliente\Services\ClienteService;
use Modules\Cliente\Requests\StoreClienteRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Modules\Cliente\Requests\UpdateClienteRequest;

class ClienteController extends Controller
{
    public function __construct(protected ClienteService $service) {}

    /**
     * Lista todos los clientes.
     */
    public function index(): JsonResponse
    {
        $clientes = $this->service->all();
        return response()->json($clientes);
    }

    /**
     * Muestra los datos de un cliente específico.
     */
    public function show(Cliente $cliente): JsonResponse
    {
        return response()->json($cliente);
    }

    /**
     * Almacena un nuevo cliente.
     */
    public function store(StoreClienteRequest $request): JsonResponse
    {
        $cliente = $this->service->create($request->validated());
        return response()->json($cliente, 201);
    }

    /**
     * Actualiza los datos de un cliente existente.
     */
    public function update(UpdateClienteRequest $request, Cliente $cliente): JsonResponse
    {
        $updated = $this->service->update($cliente, $request->validated());
        return response()->json($updated);
    }

    /**
     * Elimina un cliente.
     */
    public function destroy(Cliente $cliente): JsonResponse
    {
        $this->service->delete($cliente);
        return response()->json(['message' => 'Cliente eliminado correctamente.']);
    }
}
