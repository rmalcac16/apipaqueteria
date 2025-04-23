<?php

namespace Modules\Cliente\Controllers;

use App\Http\Controllers\Controller;
use Modules\Cliente\Services\ClienteService;
use App\Models\Cliente;
use Modules\Cliente\Requests\StoreClienteRequest;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(ClienteService $service)
    {
        return response()->json($service->all());
    }

    public function show(Cliente $cliente)
    {
        return response()->json($cliente);
    }

    public function store(StoreClienteRequest $request, ClienteService $service)
    {
        $cliente = $service->create($request->validated());
        return response()->json($cliente, 201);
    }

    public function update(StoreClienteRequest $request, Cliente $cliente, ClienteService $service)
    {
        return response()->json($service->update($cliente, $request->validated()));
    }

    public function destroy(Cliente $cliente, ClienteService $service)
    {
        $service->delete($cliente);
        return response()->json(['message' => 'Cliente eliminado.']);
    }
}
