<?php

namespace Modules\Envio\Services;

use App\Models\Envio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\SeguimientoEnvio\Services\SeguimientoEnvioService;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class EnvioService
{
    public function all(Request $request)
    {
        $query = Envio::query();
        $query->with([
            'user',
            'remitente',
            'destinatario',
            'pagador',
            'agenciaOrigen',
            'agenciaDestino',
            'pago',
            'seguimiento',
            'seguimiento.usuario',
        ]);
        return $query->latest()->get();
    }

    public function find(int $id): Envio
    {
        return Envio::with([
            'user',
            'remitente',
            'destinatario',
            'pagador',
            'agenciaOrigen',
            'agenciaDestino',
            'pago',
            'pago',
            'seguimiento',
            'seguimiento.usuario',
        ])->findOrFail($id);
    }

    public function create(array $data): Envio
    {
        $data['user_id'] = Auth::id();
        $envio = Envio::create($data);


        $envio->pago()->create([
            'monto' => $data['fleteAPagar'],
        ]);

        $envio->seguimiento()->create([
            'estado' => 'registrado',
            'descripcion' => 'El envío ha sido registrado.',
            'usuario_id' => Auth::id(),
        ]);

        if ($data['recojoDomicilio']) {
            $envio->seguimiento()->create([
                'estado' => 'en_recojo',
                'descripcion' => 'Recojo pendiente desde la dirección de origen.',
                'usuario_id' => Auth::id(),
            ]);
        } else if ($data['agencia_origen_id']) {
            $envio->seguimiento()->create([
                'estado' => 'origen',
                'descripcion' => 'El envío está en la agencia de origen.',
                'usuario_id' => Auth::id(),
            ]);
        }

        $this->generarPdfEnvio($envio);

        return $envio;
    }

    public function update(Envio $envio, array $data): Envio
    {
        $data['user_id'] = Auth::id();
        $envio->update($data);
        return $envio;
    }

    public function delete(Envio $envio): bool
    {
        return $envio->delete();
    }

    public function findByCodigo(string $codigo): ?Envio
    {
        return Envio::where('codigo', $codigo)->first();
    }

    public function registrarFlujoInicial(Envio $envio, $requiereRecogido): void
    {
        $seguimientoService = app(SeguimientoEnvioService::class);

        $seguimientoService->registrar($envio, 'registrado', 'El envío ha sido registrado.', Auth::id());

        if ($requiereRecogido) {
            $seguimientoService->registrar($envio, 'en_recojo', 'Recojo pendiente desde la dirección de origen.', Auth::id());
        }
    }


    // Cancelar Envío
    public function cancelar(Envio $envio): Envio
    {
        $envio->seguimiento()->create([
            'estado' => 'cancelado',
            'descripcion' => 'El envío ha sido cancelado por el usuario ' . Auth::user()->name . '.',
            'usuario_id' => Auth::id(),
        ]);

        $envio->pago()->update([
            'estado' => 'cancelado',
            'monto' => 0,
            'observaciones' => 'El envío ha sido cancelado el día ' . now()->format('d/m/Y H:i:s') . '.',
        ]);

        return $envio;
    }


    public function asignarViaje(Envio $envio, int $viajeId): Envio
    {
        $envio->update(['viaje_id' => $viajeId]);

        $envio->seguimiento()->create([
            'estado' => 'asignado',
            'descripcion' => 'El envío ha sido asignado al viaje #' . $viajeId,
            'usuario_id' => Auth::id(),
        ]);

        return $envio;
    }

    public function desasignarViaje(Envio $envio): Envio
    {
        $viajeAnterior = $envio->viaje_id;

        $envio->update(['viaje_id' => null]);

        $envio->seguimiento()->create([
            'estado' => 'desasignado',
            'descripcion' => 'El envío fue removido del viaje #' . $viajeAnterior,
            'usuario_id' => Auth::id(),
        ]);

        return $envio;
    }

    public function asignarMultiplesAlViaje(array $enviosIds, int $viajeId): void
    {
        foreach ($enviosIds as $id) {
            $envio = Envio::where('id', $id)->whereNull('viaje_id')->first();
            if ($envio) $this->asignarViaje($envio, $viajeId);
        }
    }

    public function desasignarMultiplesDelViaje(array $enviosIds, int $viajeId): void
    {
        foreach ($enviosIds as $id) {
            $envio = Envio::where('id', $id)->where('viaje_id', $viajeId)->first();
            if ($envio) $this->desasignarViaje($envio);
        }
    }

    // trackingEnvio

    public function trackingEnvio(string $codigo): ?Envio
    {
        return Envio::with([
            'seguimiento' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'remitente',
            'destinatario',
            'terceroPagador',
            'agenciaOrigen',
            'agenciaDestino',
            'pago',
        ])->where('codigo', $codigo)->first();
    }

    public function ticket(Envio $envio) {}


    public function generarPdfEnvio(Envio $envio): void
    {
        $envio->load([
            'user',
            'remitente',
            'destinatario',
            'pagador',
            'agenciaOrigen',
            'agenciaDestino',
            'pago',
        ]);

        $pdfA4 = Pdf::loadView('pdf.envio.a4', compact('envio'))->output();
        $pdf80 = Pdf::loadView('pdf.envio.ticket-80', compact('envio'))->setPaper([0, 0, 226.77, 600])->output();
        $pdf58 = Pdf::loadView('pdf.envio.ticket-58', compact('envio'))->setPaper([0, 0, 164.4, 600])->output();

        $a4Path = "envios/envio_{$envio->numeroOrden}_a4.pdf";
        $ticket80Path = "envios/envio_{$envio->numeroOrden}_ticket80.pdf";
        $ticket58Path = "envios/envio_{$envio->numeroOrden}_ticket58.pdf";

        Storage::disk('public')->makeDirectory('envios', 0755, true, true);

        Storage::disk('public')->put($a4Path, $pdfA4);
        Storage::disk('public')->put($ticket80Path, $pdf80);
        Storage::disk('public')->put($ticket58Path, $pdf58);

        $envio->update([
            'pdf_path_a4' => $a4Path,
            'pdf_path_ticket_80' => $ticket80Path,
            'pdf_path_ticket_58' => $ticket58Path,
        ]);
    }
}
