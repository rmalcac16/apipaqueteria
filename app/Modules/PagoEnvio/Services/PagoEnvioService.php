<?php

namespace Modules\Pago\Services;

use App\Models\PagoEnvio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Modules\Comprobante\Services\ComprobanteService;

class PagoEnvioService

{
    private ComprobanteService $comprobanteService;

    public function __construct(ComprobanteService $comprobanteService)
    {
        $this->comprobanteService = $comprobanteService;
    }


    public function getAll(): Collection
    {
        return PagoEnvio::with([
            'envio',
            'envio.remitente',
            'envio.destinatario',
            'cliente',
            'cobrador',
            'agencia',
            'comprobante',
        ])->latest()->get();
    }

    public function find(int $id): PagoEnvio
    {
        return PagoEnvio::with([
            'envio',
            'envio.remitente',
            'envio.destinatario',
            'cliente',
            'cobrador',
            'agencia',
            'comprobante',
        ])->findOrFail($id);
    }

    public function create(array $data): PagoEnvio
    {
        return DB::transaction(function () use ($data) {
            $data['cobrado_por'] = $data['cobrado_por'] ?? Auth::id();

            $comprobanteData = $data['comprobante'] ?? null;
            unset($data['comprobante']);

            $pago = PagoEnvio::create($data);

            if ($comprobanteData) {
                $this->comprobanteService->crearDesdePago($pago, $comprobanteData);
            }

            return $this->find($pago->id);
        });
    }

    public function update(int $id, array $data): PagoEnvio
    {
        return DB::transaction(function () use ($id, $data) {
            $pago = PagoEnvio::findOrFail($id);
            $pago->update($data);

            return $this->find($pago->id);
        });
    }

    public function delete(int $id): void
    {
        DB::transaction(function () use ($id) {
            $pago = PagoEnvio::findOrFail($id);
            $pago->delete();
        });
    }
}
