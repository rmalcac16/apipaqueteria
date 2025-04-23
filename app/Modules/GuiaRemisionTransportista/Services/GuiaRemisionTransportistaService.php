<?php

namespace Modules\GuiaRemisionTransportista\Services;

use App\Models\GuiaRemisionTransportista;
use App\Models\Envio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

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
            $serie = 'V001';
            $ultimoNumero = GuiaRemisionTransportista::where('serie', $serie)->max('numero') ?? 0;
            $nuevoNumero = $ultimoNumero + 1;

            $guia = GuiaRemisionTransportista::create([
                'envio_id'              => $envio->id,
                'viaje_id'              => null,
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

            return $guia;
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
}
