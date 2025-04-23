<?php

namespace Modules\Envio\Services;

use App\Models\Envio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

use Modules\GuiaRemisionTransportista\Services\GuiaRemisionTransportistaService;

class EnvioService
{

    protected GuiaRemisionTransportistaService $guiaRemisionTransportistaService;

    public function __construct(GuiaRemisionTransportistaService $guiaRemisionTransportistaService)
    {
        $this->guiaRemisionTransportistaService = $guiaRemisionTransportistaService;
    }


    /**
     * Listar todos los envíos con relaciones.
     */
    public function getAll(): Collection
    {
        return Envio::with([
            'remitente',
            'destinatario',
            'pagador',
            'agenciaOrigen',
            'agenciaDestino',
            'items',
        ])->latest()->get();
    }

    /**
     * Buscar un envío por su ID con relaciones.
     */
    public function find(int $id): Envio
    {
        return Envio::with([
            'remitente',
            'destinatario',
            'pagador',
            'agenciaOrigen',
            'agenciaDestino',
            'items',
        ])->findOrFail($id);
    }

    public function create(array $data): Envio
    {
        return DB::transaction(function () use ($data) {
            $data['user_id'] = $data['user_id'] ?? Auth::id() ?? 1;
            $items = $data['items'] ?? [];
            unset($data['items']);

            $documentosSustento = $data['documentos_sustento'] ?? [];
            unset($data['documentos_sustento']);

            $envio = Envio::create($data);

            foreach ($items as $item) {
                $envio->items()->create($item);
            }

            $this->guiaRemisionTransportistaService->createFromEnvio($envio, $documentosSustento);

            return $this->find($envio->id);
        });
    }

    public function update(int $id, array $data): Envio
    {
        return DB::transaction(function () use ($id, $data) {
            $envio = Envio::findOrFail($id);

            $items = $data['items'] ?? [];
            unset($data['items']);

            $envio->update($data);

            $envio->items()->delete();
            foreach ($items as $item) {
                $envio->items()->create($item);
            }

            return $this->find($envio->id);
        });
    }

    /**
     * Eliminar un envío (con ítems por cascada).
     */
    public function delete(int $id): void
    {
        DB::transaction(function () use ($id) {
            $envio = Envio::findOrFail($id);
            $envio->delete();
        });
    }
}
