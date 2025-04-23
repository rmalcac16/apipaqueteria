<?php

namespace Modules\Cliente\Services;

use App\Models\Cliente;
use Illuminate\Support\Collection;

class ClienteService
{
    /**
     * Obtiene todos los clientes ordenados por fecha de creación descendente.
     */
    public function all(): Collection
    {
        return Cliente::latest()->get();
    }

    /**
     * Encuentra un cliente por su ID o lanza una excepción.
     */
    public function find(int $id): Cliente
    {
        return Cliente::findOrFail($id);
    }

    /**
     * Crea un nuevo cliente.
     */
    public function create(array $data): Cliente
    {
        return Cliente::create($data);
    }

    /**
     * Actualiza un cliente existente.
     */
    public function update(Cliente $cliente, array $data): Cliente
    {
        $cliente->update($data);
        return $cliente->refresh(); // asegura que el modelo esté actualizado
    }

    /**
     * Elimina un cliente.
     */
    public function delete(Cliente $cliente): bool
    {
        return (bool) $cliente->delete();
    }
}
