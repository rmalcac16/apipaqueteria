<?php

namespace Modules\Viaje\Services;

use App\Models\Viaje;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\Envio\Services\EnvioService;

class ViajeService
{

    public function create(array $data): Viaje
    {
        return DB::transaction(function () use ($data) {
            $this->validarAgencias($data['agencia_origen_id'], $data['agencia_destino_id']);

            $data['codigo'] = $this->generarCodigo();
            $data['estado'] = Viaje::ESTADO_PROGRAMADO;
            $data['estado_legible'] = 'Programado';
            $data['manifiesto_generado'] = false;
            $data['viaje_urgente'] = $data['viaje_urgente'] ?? false;
            $data['sincronizado_con_sunat'] = false;
            $data['user_id'] = Auth::id();

            return Viaje::create($data);
        });
    }

    public function update(Viaje $viaje, array $data): Viaje
    {
        return DB::transaction(function () use ($viaje, $data) {
            if (isset($data['agencia_origen_id'], $data['agencia_destino_id'])) {
                $this->validarAgencias($data['agencia_origen_id'], $data['agencia_destino_id']);
            }

            $viaje->update($data);

            return $viaje;
        });
    }

    public function cambiarConductor(Viaje $viaje, int $conductorId): Viaje
    {
        $viaje->update(['conductor_id' => $conductorId]);
        return $viaje;
    }

    public function cambiarVehiculo(Viaje $viaje, int $vehiculoId): Viaje
    {
        $viaje->update(['vehiculo_id' => $vehiculoId]);
        return $viaje;
    }

    public function iniciar(Viaje $viaje): Viaje
    {
        if ($viaje->estado !== Viaje::ESTADO_PROGRAMADO) {
            throw ValidationException::withMessages([
                'estado' => 'Solo se puede iniciar un viaje programado.',
            ]);
        }

        $viaje->update([
            'estado' => Viaje::ESTADO_EN_TRANSITO,
            'hora_salida_real' => Carbon::now()->format('H:i'),
            'estado_legible' => 'En tránsito',
        ]);

        return $viaje;
    }

    public function finalizar(Viaje $viaje): Viaje
    {
        if ($viaje->estado !== Viaje::ESTADO_EN_TRANSITO) {
            throw ValidationException::withMessages([
                'estado' => 'Solo se puede finalizar un viaje en tránsito.',
            ]);
        }

        $viaje->update([
            'estado' => Viaje::ESTADO_FINALIZADO,
            'fecha_llegada_real' => Carbon::now(),
            'estado_legible' => 'Finalizado',
        ]);

        return $viaje;
    }

    public function cancelar(Viaje $viaje, string $motivo = null): Viaje
    {
        if ($viaje->estado === Viaje::ESTADO_FINALIZADO) {
            throw ValidationException::withMessages([
                'estado' => 'No se puede cancelar un viaje finalizado.',
            ]);
        }

        $viaje->update([
            'estado' => Viaje::ESTADO_CANCELADO,
            'estado_legible' => 'Cancelado',
            'observaciones' => $motivo,
        ]);

        return $viaje;
    }

    public function delete(Viaje $viaje): void
    {
        if ($viaje->envios()->exists()) {
            throw ValidationException::withMessages([
                'viaje' => 'No se puede eliminar un viaje con envíos asociados.',
            ]);
        }

        $viaje->delete();
    }

    public function buscar(array $filtros, int $perPage = 10)
    {
        $query = Viaje::with([
            'vehiculo',
            'conductor',
            'agenciaOrigen',
            'agenciaDestino',
        ])->latest();

        if (!empty($filtros['codigo'])) {
            $query->where('codigo', 'like', '%' . $filtros['codigo'] . '%');
        }

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['agencia_origen_id']) && $filtros['agencia_origen_id'] !== 'null') {
            $query->where('agencia_origen_id', $filtros['agencia_origen_id']);
        }

        if (!empty($filtros['agencia_destino_id']) && $filtros['agencia_destino_id'] !== 'null') {
            $query->where('agencia_destino_id', $filtros['agencia_destino_id']);
        }

        if (!empty($filtros['fecha_salida'])) {
            $query->whereDate('fecha_salida', $filtros['fecha_salida']);
        }

        if (!empty($filtros['fecha_salida_inicio'])) {
            $query->whereDate('fecha_salida', '>=', $filtros['fecha_salida_inicio']);
            $query->whereDate('fecha_salida', '<=', Carbon::parse($filtros['fecha_salida_inicio'])->addDays(3)->format('Y-m-d'));
        }

        return $query->get();
    }

    public function ver(int $id): Viaje
    {
        return Viaje::with([
            'vehiculo',
            'conductor',
            'agenciaOrigen',
            'agenciaDestino',
            'envios',
        ])->findOrFail($id);
    }

    protected function generarCodigo(): string
    {
        $lastId = Viaje::max('id') ?? 0;
        return 'VIA-' . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);
    }

    protected function validarAgencias(int $origenId, int $destinoId): void
    {
        if ($origenId === $destinoId) {
            throw ValidationException::withMessages([
                'agencias' => 'La agencia de origen no puede ser igual a la de destino.',
            ]);
        }
    }

    public function asignarEnvios(Viaje $viaje, array $enviosIds): void
    {
        app(EnvioService::class)->asignarMultiplesAlViaje($enviosIds, $viaje->id);
    }

    public function desasignarEnvios(Viaje $viaje, array $enviosIds): void
    {
        app(EnvioService::class)->desasignarMultiplesDelViaje($enviosIds, $viaje->id);
    }
}
