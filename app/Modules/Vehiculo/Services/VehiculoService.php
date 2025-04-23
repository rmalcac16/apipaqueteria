<?php

namespace Modules\Vehiculo\Services;

use App\Events\VehiculosUpdated;
use App\Models\Vehiculo;

class VehiculoService
{
    public function all(array $filtros)
    {
        $query = Vehiculo::query()->with('carreta');

        $query->when(isset($filtros['placa']), function ($query) use ($filtros) {
            $query->where('placa', 'like', '%' . $filtros['placa'] . '%');
        });

        $query->when(isset($filtros['tipo']), function ($query) use ($filtros) {
            $query->where('tipo', $filtros['tipo']);
        });

        return $query->get();
    }

    public function find(int $id)
    {
        return Vehiculo::with('carreta')->findOrFail($id);
    }

    public function create(array $data): Vehiculo
    {
        $data = Vehiculo::create($data);
        return $data;
    }

    public function update(Vehiculo $vehiculo, array $data): Vehiculo
    {
        $vehiculo->update($data);
        return $vehiculo;
    }

    public function delete(Vehiculo $vehiculo): bool
    {
        $vehiculo->delete();
        return true;
    }
}
