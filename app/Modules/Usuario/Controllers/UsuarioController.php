<?php

namespace Modules\Usuario\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Modules\Usuario\Requests\StoreUsuarioRequest;
use Modules\Usuario\Requests\UpdateUsuarioRequest;
use Modules\Usuario\Services\UsuarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class UsuarioController extends Controller
{
    public function __construct(protected UsuarioService $service) {}

    public function index(Request $request): Collection
    {
        return $this->service->all($request->all());
    }

    public function show($id): JsonResponse
    {
        return response()->json($this->service->find($id));
    }

    public function store(StoreUsuarioRequest $request): JsonResponse
    {
        $usuario = $this->service->create($request->validated());
        return response()->json($usuario, 201);
    }

    public function update(UpdateUsuarioRequest $request, User $usuario): JsonResponse
    {
        $usuario = $this->service->update($usuario, $request->validated());
        return response()->json($usuario);
    }

    public function destroy(User $usuario): JsonResponse
    {
        $this->service->delete($usuario);
        return response()->json(['message' => 'Usuario eliminado']);
    }
}
