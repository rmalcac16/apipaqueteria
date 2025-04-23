<?php

namespace Modules\Cliente\Services;

use App\Models\Cliente;

class ClienteService
{
    public function all()
    {
        return Cliente::latest()->get();
    }

    public function find(int $id): Cliente
    {
        return Cliente::findOrFail($id);
    }

    public function create(array $data): Cliente
    {
        return Cliente::create($data);
    }

    public function update(Cliente $cliente, array $data): Cliente
    {
        $cliente->update($data);
        return $cliente;
    }

    public function delete(Cliente $cliente): bool
    {
        return $cliente->delete();
    }
}
