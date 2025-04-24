<?php

namespace Modules\Comprobante\Services;

use App\Models\Comprobante;
use App\Models\CuotaComprobante;
use App\Models\PagoEnvio;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Luecano\NumeroALetras\NumeroALetras;
use Modules\ComprobanteSunat\Services\SunatComprobanteService;
use Throwable;

class ComprobanteService
{

    private  $sunatComprobanteService;

    public function __construct(SunatComprobanteService $sunatComprobanteService)
    {
        $this->sunatComprobanteService = $sunatComprobanteService;
    }


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
            $tipo = $data['tipo'] ?? '03';
            $serie = $tipo === '01' ? env('SERIE_FACTURA', 'F101') : env('SERIE_BOLETA', 'B101');

            $ultimo = Comprobante::where('tipo', $tipo)
                ->where('serie', $serie)
                ->max('numero') ?? 0;

            $numero = $ultimo + 1;

            $comprobante = Comprobante::create([
                'pago_envio_id' => $pago->id,
                'tipo'          => $tipo,
                'serie'         => $serie,
                'numero'        => $numero,
                'forma_pago'    => $data['forma_pago'],
                'monto_total'   => $pago->monto,
                'estado'        => 'generado',
                'estado_sunat'  => 'pendiente',
                'fecha_emision' => now(),
                'cliente_id'    => $pago->realizado_por,
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

            return tap($this->find($comprobante->id), function ($comprobante) {
                if (env('COMPROBANTE_SUNAT_AUTO', false)) {
                    try {
                        $this->enviarSunat($comprobante);
                    } catch (Throwable $e) {
                        Log::error('Error al enviar comprobante a SUNAT: ' . $e->getMessage());
                    }
                }
            });
        });
    }


    public function enviarSunat(Comprobante $comprobante): array
    {
        $items = [];

        if ($comprobante->pago && $comprobante->pago->envio && $comprobante->pago->envio->items->count()) {
            $envioItems = $comprobante->pago->envio->items;
            $montoPorItem = $comprobante->monto_total / $envioItems->count();

            foreach ($envioItems as $item) {
                $valorUnitario = ($montoPorItem / $item->cantidad) / 1.18;
                $baseIgv = $valorUnitario * $item->cantidad;
                $igv = $baseIgv * 0.18;
                $precioUnitario = $valorUnitario * 1.18;

                $items[] = [
                    'codigo' => $item->codigo ?? 'TRANS-001',
                    'unidad' => 'ZZ',
                    'cantidad' => $item->cantidad,
                    'descripcion' => 'Servicio de transporte ' . $item->descripcion,
                    'valor_unitario' => round($valorUnitario, 2),
                    'base_igv' => round($baseIgv, 2),
                    'igv' => round($igv, 2),
                    'valor_total' => round($baseIgv, 2),
                    'precio_unitario' => round($precioUnitario, 2),
                ];
            }
        } else {
            $valor = $comprobante->monto_total / 1.18;
            $igv = $valor * 0.18;
            $precio = $valor + $igv;

            $items[] = [
                'codigo' => 'SERV-GENERAL',
                'unidad' => 'ZZ',
                'cantidad' => 1,
                'descripcion' => 'Servicio de transporte de carga',
                'valor_unitario' => round($valor, 2),
                'base_igv' => round($valor, 2),
                'igv' => round($igv, 2),
                'valor_total' => round($valor, 2),
                'precio_unitario' => round($precio, 2),
            ];
        }


        $data = [
            'tipo' => $comprobante->tipo,
            'serie' => $comprobante->serie,
            'correlativo' => $comprobante->numero,
            'cliente' => [
                'tipo_doc' => $comprobante->cliente->tipoDocumento,
                'num_doc' => $comprobante->cliente->numeroDocumento,
                'razon_social' => $comprobante->cliente->nombreCompleto,
            ],
            'empresa' => [
                'ruc' => env('BUSINESS_RUC'),
                'razon_social' => env('BUSINESS_NAME'),
                'nombre_comercial' => env('BUSINESS_NAME_COMMERCIAL'),
                'ubigeo' => env('BUSINESS_UBIGEO'),
                'direccion' => env('BUSINESS_ADDRESS'),
                'departamento' => env('BUSINESS_DEPARTAMENTO'),
                'provincia' => env('BUSINESS_PROVINCIA'),
                'distrito' => env('BUSINESS_DISTRITO'),
            ],
            'total_gravadas' => round($comprobante->monto_total / 1.18, 2),
            'igv' => round($comprobante->monto_total * 0.18 / 1.18, 2),
            'total' => round($comprobante->monto_total, 2),
            'leyenda' => 'SON ' . strtoupper((new NumeroALetras())->toWords($comprobante->monto_total, 2, 'SOLES')),
            'items' => $items
        ];

        return $this->sunatComprobanteService->emitirComprobante($data, $comprobante);
    }
}
