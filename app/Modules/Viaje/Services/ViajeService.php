<?php

namespace Modules\Viaje\Services;

use App\Models\Viaje;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class ViajeService
{
    /**
     * Obtener todos los viajes.
     */
    public function getAll(array $filters = []): Collection
    {
        $query = Viaje::with([
            'vehiculoPrincipal',
            'vehiculoSecundario',
            'conductorPrincipal',
            'conductorSecundario',
            'agenciaOrigen',
            'agenciaDestino',
            'creadoPor',
        ]);

        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                if (!is_null($value)) {
                    if ($key === 'fecha_inicio_salida') {
                        $query->where('fecha_salida', '>=', $value);
                    } elseif ($key === 'estado_in') {
                        $query->whereIn('estado', $value);
                    } elseif ($key === 'estado_not_in') {
                        $query->whereNotIn('estado', $value);
                    } else {
                        $query->where($key, $value);
                    }
                }
            }
        }

        // Ordenar por fecha de salida
        $query->orderBy('fecha_salida', 'asc');

        return $query->get();
    }

    /**
     * Buscar un viaje por ID.
     */
    public function find(int $id): Viaje
    {
        return Viaje::with([
            'vehiculoPrincipal',
            'vehiculoSecundario',
            'conductorPrincipal',
            'conductorSecundario',
            'agenciaOrigen',
            'agenciaDestino',
            'creadoPor',
        ])->findOrFail($id);
    }

    /**
     * Registrar un nuevo viaje.
     */
    public function store(array $data): Viaje
    {
        return DB::transaction(fn() => Viaje::create($data));
    }

    /**
     * Actualizar un viaje existente por ID.
     */
    public function updateById(int $id, array $data): Viaje
    {
        return DB::transaction(function () use ($id, $data) {
            $viaje = $this->find($id);
            $viaje->update($data);
            return $viaje;
        });
    }

    /**
     * Eliminar un viaje por ID.
     */
    public function deleteById(int $id): void
    {
        DB::transaction(function () use ($id) {
            $viaje = $this->find($id);
            $viaje->delete();
        });
    }
}
