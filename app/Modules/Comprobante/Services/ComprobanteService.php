<?php

namespace Modules\Comprobante\Services;

use App\Models\Comprobante;
use App\Models\CuotaComprobante;
use App\Models\PagoEnvio;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class ComprobanteService
{
    /**
     * Listar todos los comprobantes con sus relaciones
     */
    public function getAll(): Collection
    {
        return Comprobante::with([
            'pago.envio',
            'pago.envio.items',
            'pago.envio.guiaRemision',
            'cliente',
            'cuotas',
            'pago'
        ])->latest()->get();
    }

    /**
     * Ver un comprobante por ID
     */
    public function find(int $id): Comprobante
    {
        return Comprobante::with([
            'pago.envio',
            'pago.envio.items',
            'pago.envio.guiaRemision',
            'cliente',
            'cuotas',
            'pago'
        ])->findOrFail($id);
    }

    /**
     * Crear comprobante a partir de un pago de envío
     */
    public function crearDesdePago(PagoEnvio $pago, array $data): Comprobante
    {
        return DB::transaction(function () use ($pago, $data) {
            $tipo = $data['tipo'] ?? '03'; // boleta por defecto
            $serie = $tipo === '01' ? 'F001' : 'B001';

            $ultimo = Comprobante::where('tipo', $tipo)
                ->where('serie', $serie)
                ->max('numero') ?? 0;

            $numero = $ultimo + 1;

            $comprobante = Comprobante::create([
                'pago_envio_id' => $pago->id,
                'tipo'          => $tipo,
                'serie'         => $serie,
                'numero'        => $numero,
                'forma_pago'    => $data['forma_pago'], // contado o crédito
                'monto_total'   => $pago->monto,
                'estado'        => 'generado',
                'estado_sunat'  => 'pendiente',
                'fecha_emision' => now(),
                'cliente_id'    => $pago->envio->pagador_id,
            ]);

            // Si es a crédito, registrar cuotas
            if ($data['forma_pago'] === 'credito') {
                $cuotas = $data['cuotas'] ?? [];

                foreach ($cuotas as $index => $cuota) {
                    CuotaComprobante::create([
                        'comprobante_id'    => $comprobante->id,
                        'numero_cuota'      => $index + 1,
                        'monto'             => $cuota['monto'],
                        'fecha_vencimiento' => $cuota['fecha_vencimiento'],
                        'estado'            => 'pendiente',
                    ]);
                }
            }

            return $this->find($comprobante->id);
        });
    }
}
