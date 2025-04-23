<?php

namespace Modules\PagoEnvio\Services;

use App\Models\PagoEnvio;
use App\Models\Envio;
use App\Models\User;
use App\Models\Agencia;
use App\Models\Comprobante;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Modules\Comprobantes\Services\ComprobanteService;

class PagoEnvioService
{
    public function all()
    {
        return PagoEnvio::latest()->with(['envio', 'usuario', 'agencia', 'cliente'])->get();
    }

    public function find($id): PagoEnvio
    {
        return PagoEnvio::with(['envio', 'usuario', 'agencia', 'cliente'])->findOrFail($id);
    }


    public function findByEnvioId($envioId): PagoEnvio
    {
        return PagoEnvio::with(['envio', 'usuario', 'agencia', 'cliente'])->where('envio_id', $envioId)->firstOrFail();
    }


    public function create(array $data): PagoEnvio
    {
        $pago = PagoEnvio::create($data);
        $pago->update([
            'fecha_pago' => now(),
            'estado' => 'completado',
            'cobrado_por' => Auth::id()
        ]);

        app(ComprobanteService::class)->crearComprobante($pago, $data['tipo_comprobante']);

        $pago->load('comprobante');

        return $pago;
    }


    public function crearPagoPendiente(Envio $envio): PagoEnvio
    {
        return PagoEnvio::create([
            'envio_id' => $envio->id,
            'monto' => $envio->flete_estimado,
            'estado' => 'pendiente',
            'forma_pago' => 'efectivo',
            'agencia_id' => $envio->agencia_origen_id,
            'realizado_por' => null,
            'cobrado_por' => null,
        ]);
    }

    /**
     * ✅ Actualiza un pago (usado para completar o cancelar)
     */
    public function update(PagoEnvio $pago, array $data): PagoEnvio
    {
        $envio = $pago->envio;

        if (isset($data['monto']) && $data['monto'] > $envio->flete_estimado) {
            throw ValidationException::withMessages([
                'monto' => 'El monto no puede ser mayor al flete estimado del envío.'
            ]);
        }

        $pago->update($data);

        if ($data['estado'] === 'cancelado') {
            $envio->update(['estado' => 'cancelado']);
        }


        return $pago;
    }

    public function delete(PagoEnvio $pago): bool
    {
        return $pago->delete();
    }


    public function cancelarPago(PagoEnvio $pago): PagoEnvio
    {
        $pago->update(['estado' => 'cancelado']);
        $pago->envio->update(['estado' => 'cancelado']);
        return $pago;
    }


    public function reporteDinamico($agenciaId = null, $fechaInicio = null, $fechaFin = null, $formaPago = null, $usuarioId = null)
    {
        $query = PagoEnvio::query()->with('envio', 'agencia', 'usuario', 'cliente');

        if ($agenciaId) {
            $query->where('agencia_id', $agenciaId);
        }

        if ($fechaInicio) {
            $query->whereDate('created_at', '>=', $fechaInicio);
        }

        if ($fechaFin) {
            $query->whereDate('created_at', '<=', $fechaFin);
        }

        if ($formaPago) {
            $query->where('forma_pago', $formaPago);
        }

        if ($usuarioId) {
            $query->where('cobrado_por', $usuarioId);
        }

        $pagos = $query->orderBy('created_at', 'desc')->get();
        $total = $pagos->sum('monto');

        return [
            'pagos' => $pagos,
            'total' => $total,
            'filtros' => [
                'agencia' => $agenciaId ? Agencia::find($agenciaId)?->nombre : 'Todas',
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'forma_pago' => $formaPago ?? 'Todas',
                'usuario' => $usuarioId ? User::find($usuarioId)?->nombreCompleto : 'Todos',
            ],
        ];
    }





    // Pagar

    public function pagarEnvio(PagoEnvio $pagoEnvio, array $data): PagoEnvio
    {
        // Actualizar estado del pago a completado
        $pagoEnvio->update([
            'estado' => 'completado',
            'fecha_pago' => now(),
            'cobrado_por' => Auth::id(),
            'realizado_por' => $pagoEnvio->envio->pagador_id,
            'forma_pago' => $data['forma_pago'],
            'medio_pago' => $data['medio_pago'] ?? null,
            'numero_transaccion' => $data['numero_transaccion'] ?? null,
            'observaciones' => $data['observaciones'] ?? null,
        ]);

        return $pagoEnvio;
    }
}
