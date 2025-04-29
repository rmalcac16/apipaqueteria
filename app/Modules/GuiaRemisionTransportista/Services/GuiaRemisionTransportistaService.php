<?php

namespace Modules\GuiaRemisionTransportista\Services;

use App\Models\GuiaRemisionTransportista;
use App\Models\Envio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Modules\GuiaRemisionSunat\Services\SunatGuiaRemisionService;
use Luecano\NumeroALetras\NumeroALetras;
use Throwable;

class GuiaRemisionTransportistaService
{
    /**
     * Obtener todas las guías con sus relaciones.
     */
    public function getAll(): Collection
    {
        return GuiaRemisionTransportista::with([
            'envio.remitente',
            'envio.destinatario',
            'envio.pagador',
            'envio.items',
            'viaje.vehiculoPrincipal',
            'viaje.vehiculoSecundario',
            'viaje.conductorPrincipal',
            'viaje.conductorSecundario',
            'viaje',
            'envio',
            'envio.agenciaOrigen',
            'envio.agenciaOrigen',
            'documentosSustento'
        ])->latest()->get();
    }

    /**
     * Buscar una guía con relaciones por ID.
     */
    public function find(int $id): GuiaRemisionTransportista
    {
        return GuiaRemisionTransportista::with([
            'envio.remitente',
            'envio.destinatario',
            'envio.pagador',
            'envio.items',
            'viaje.vehiculoPrincipal',
            'viaje.vehiculoSecundario',
            'viaje.conductorPrincipal',
            'viaje.conductorSecundario',
            'viaje',
            'envio',
            'envio.agenciaOrigen',
            'envio.agenciaOrigen',
            'documentosSustento'
        ])->findOrFail($id);
    }

    /**
     * Crear una nueva guía a partir de un envío.
     */
    public function createFromEnvio(Envio $envio, array $documentosSustento = []): GuiaRemisionTransportista
    {
        return DB::transaction(function () use ($envio, $documentosSustento) {
            $serie = env('SERIE_GUIA_TRANSPORTISTA', 'V101');
            $ultimoNumero = GuiaRemisionTransportista::where('serie', $serie)->max('numero') ?? 0;
            $nuevoNumero = $ultimoNumero + 1;

            $guia = GuiaRemisionTransportista::create([
                'envio_id'              => $envio->id,
                'viaje_id'              => $envio->viaje_id,
                'codigo'                => $serie . '-' . str_pad($nuevoNumero, 6, '0', STR_PAD_LEFT),
                'serie'                 => $serie,
                'numero'                => $nuevoNumero,
                'fecha_emision'         => now(),
                'fecha_inicio_traslado' => $envio->fechaTraslado ?? today(),
                'estado'                => 'generada',
                'estado_sunat'          => 'pendiente',
                'user_id'               => Auth::id() ?? 1,
            ]);

            foreach ($documentosSustento as $doc) {
                $guia->documentosSustento()->create([
                    'tipo_documento' => $doc['tipo_documento'],
                    'serie_numero'   => $doc['serie_numero'],
                    'ruc_emisor'     => $doc['ruc_emisor'],
                ]);
            }

            return tap($this->find($guia->id), function ($guia) {
                if (getSetting('GUIA_REMISION_SUNAT_AUTO', false)) {
                    try {
                        $this->enviarSunat($guia);
                    } catch (Throwable $e) {
                        Log::error('Error al enviar la guia de remisión a SUNAT: ' . $e->getMessage());
                    }
                }
            });
        });
    }

    /**
     * Actualizar una guía existente.
     */
    public function update(int $id, array $data): GuiaRemisionTransportista
    {
        return DB::transaction(function () use ($id, $data) {
            $guia = GuiaRemisionTransportista::findOrFail($id);
            $guia->update($data);
            return $this->find($guia->id);
        });
    }

    /**
     * Eliminar una guía.
     */
    public function delete(int $id): void
    {
        DB::transaction(function () use ($id) {
            $guia = GuiaRemisionTransportista::findOrFail($id);
            $guia->delete();
        });
    }


    public function enviarSunat(GuiaRemisionTransportista $guia_remision_transportista): array
    {

        $envio = $guia_remision_transportista->envio;
        $conductor = $guia_remision_transportista->viaje->conductorPrincipal;
        $vehiculo = $guia_remision_transportista->viaje->vehiculoPrincipal;
        $nombreSeparado = $this->separarNombresApellidos($conductor->nombreCompleto);

        $items = [];
        foreach ($envio->items as $item) {
            $items[] = [
                'codigo' => $item->codigo,
                'descripcion' => $item->descripcion,
                'unidad' => $item->unidad_medida,
                'cantidad' => $item->cantidad,
            ];
        }

        $data = [
            'serie' => $guia_remision_transportista->serie ?? getSetting('SERIE_GUIA_TRANSPORTISTA'),
            'correlativo' => $guia_remision_transportista->numero ?? '00000001',
            'empresa' => [
                'ruc' =>  getSetting('SUNAT_GUIA_RUC', '20161515648'),
                'razon_social' => getSetting('BUSINESS_NAME'),
                'nro_mtc' => getSetting('BUSINESS_MTC') ?? '0000000001'
            ],
            'destinatario' => [
                'tipo_doc' => $envio->destinatario->tipoDocumento,
                'numero_doc' => $envio->destinatario->numeroDocumento,
                'razon_social' => $envio->destinatario->nombreCompleto,
            ],
            'remitente' => [
                'tipo_doc' => $envio->remitente->tipoDocumento,
                'numero_doc' => $envio->remitente->numeroDocumento,
                'razon_social' => $envio->remitente->nombreCompleto,
            ],
            'vehiculo' => [
                'placa' => $vehiculo->placa,
            ],
            'chofer' => [
                'dni' => $conductor->numeroDocumento,
                'licencia' => $conductor->numeroLicencia,
                'nombres' => $nombreSeparado['nombres'],
                'apellidos' => $nombreSeparado['apellidos'],
            ],
            'envio' => [
                'fecha_traslado' => $envio->fechaTraslado ?? now()->toDateTimeString(),
                'peso_total' => $envio->pesoTotal ?? 1,
                'unidad_medida' => $envio->unidadMedida ?? 'KGM',
                'ubigeo_origen' => $envio->recojoDomicilio ? $envio->recojo_ubigeo : $envio->agenciaOrigen->ubigeo,
                'direccion_origen' => $envio->recojoDomicilio ? $envio->recojo_direccion : $envio->agenciaOrigen->direccion,
                'ubigeo_destino' => $envio->entregaDomicilio ? $envio->entrega_ubigeo : $envio->agenciaDestino->ubigeo,
                'direccion_destino' => $envio->entregaDomicilio ? $envio->entrega_direccion : $envio->agenciaDestino->direccion,
            ],
            'items' => $items
        ];

        $service = new SunatGuiaRemisionService();
        return $service->emitirGuia($data, $guia_remision_transportista);
    }


    private function separarNombresApellidos(string $nombreCompleto): array
    {
        $partes = explode(' ', trim($nombreCompleto));
        $total = count($partes);

        if ($total >= 3) {
            $apellidos = $partes[$total - 2] . ' ' . $partes[$total - 1];
            $nombres = implode(' ', array_slice($partes, 0, $total - 2));
        } elseif ($total === 2) {
            $nombres = $partes[0];
            $apellidos = $partes[1];
        } else {
            $nombres = $nombreCompleto;
            $apellidos = '';
        }

        return [
            'nombres' => $nombres,
            'apellidos' => $apellidos,
        ];
    }
}
