<?php

namespace Modules\Vehiculo\Services;

use App\Models\Vehiculo;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class VehiculoService
{
    public function getAll(): Collection
    {
        return Vehiculo::all();
    }

    public function find(int $id): Vehiculo
    {
        return Vehiculo::findOrFail($id);
    }

    public function store(array $data): Vehiculo
    {
        return DB::transaction(fn() => Vehiculo::create($data));
    }

    public function updateById(int $id, array $data): Vehiculo
    {
        return DB::transaction(function () use ($id, $data) {
            $vehiculo = $this->find($id);
            $vehiculo->update($data);
            return $vehiculo;
        });
    }

    public function deleteById(int $id): void
    {
        DB::transaction(function () use ($id) {
            $vehiculo = $this->find($id);
            $vehiculo->delete();
        });
    }
}
