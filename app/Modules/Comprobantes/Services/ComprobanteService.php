<?php

namespace Modules\Comprobantes\Services;

use App\Models\Comprobante;
use App\Models\SerieComprobante;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ComprobanteService
{
    public function crear(array $data): Comprobante
    {
        $serie = SerieComprobante::where('tipo_comprobante', $data['tipo_comprobante'])
            ->where('serie', $data['serie'])
            ->where('estado', 'activa')
            ->firstOrFail();

        $ultimo = Comprobante::where('tipo_comprobante', $data['tipo_comprobante'])
            ->where('serie', $data['serie'])
            ->orderByDesc('id')
            ->first();

        $data['correlativo'] = $ultimo
            ? str_pad((int) $ultimo->correlativo + 1, 8, '0', STR_PAD_LEFT)
            : '00000001';

        // Crear comprobante
        $comprobante = Comprobante::create($data);

        // Generar PDFs
        $pdfA4 = Pdf::loadView('pdf.comprobante-a4', compact('comprobante'))->output();
        $pdf80 = Pdf::loadView('pdf.comprobante-ticket-80', compact('comprobante'))->setPaper([0, 0, 226.77, 600])->output();
        $pdf58 = Pdf::loadView('pdf.comprobante-ticket-58', compact('comprobante'))->setPaper([0, 0, 164.4, 600])->output();

        $a4Path = "comprobantes/comprobante_{$comprobante->id}_a4.pdf";
        $ticket80Path = "comprobantes/comprobante_{$comprobante->id}_ticket80.pdf";
        $ticket58Path = "comprobantes/comprobante_{$comprobante->id}_ticket58.pdf";

        Storage::disk('public')->put($a4Path, $pdfA4);
        Storage::disk('public')->put($ticket80Path, $pdf80);
        Storage::disk('public')->put($ticket58Path, $pdf58);

        $comprobante->update([
            'pdf_path_a4' => $a4Path,
            'pdf_path_ticket_80' => $ticket80Path,
            'pdf_path_ticket_58' => $ticket58Path,
        ]);

        return $comprobante;
    }


    // Crear comprobante de pago

    public function crearComprobante($pagoEnvio, $tipoComprobante = '03'): Comprobante
    {
        $comprobante = Comprobante::create([
            'pago_envios_id' => $pagoEnvio->id,
            'tipo_comprobante' => $tipoComprobante,
            'cliente_tipo_documento' => $pagoEnvio->cliente->tipo_documento,
            'cliente_numero_documento' => $pagoEnvio->cliente->documento,
            'cliente_nombre' => $pagoEnvio->cliente->nombre_completo,
            'cliente_direccion' => $pagoEnvio->cliente->direccion,
            'monto_total' => $pagoEnvio->monto,
            'fecha_emision' => now(),
        ]);

        $this->generarPdfComprobante($comprobante);

        return $comprobante;
    }


    public function generarPdfComprobante($comprobante): void
    {
        $pdfA4 = Pdf::loadView('pdf.comprobante-a4', compact('comprobante'))->output();
        $pdf80 = Pdf::loadView('pdf.comprobante-ticket-80', compact('comprobante'))->setPaper([0, 0, 226.77, 600])->output();
        $pdf58 = Pdf::loadView('pdf.comprobante-ticket-58', compact('comprobante'))->setPaper([0, 0, 164.4, 600])->output();

        if (!Storage::disk('public')->exists('comprobantes')) {
            Storage::disk('public')->makeDirectory('comprobantes');
        }

        $a4Path = "comprobantes/comprobante_{$comprobante->id}_a4.pdf";
        $ticket80Path = "comprobantes/comprobante_{$comprobante->id}_ticket80.pdf";
        $ticket58Path = "comprobantes/comprobante_{$comprobante->id}_ticket58.pdf";

        Storage::disk('public')->put($a4Path, $pdfA4);
        Storage::disk('public')->put($ticket80Path, $pdf80);
        Storage::disk('public')->put($ticket58Path, $pdf58);

        $comprobante->update([
            'pdf_path_a4' => $a4Path,
            'pdf_path_ticket_80' => $ticket80Path,
            'pdf_path_ticket_58' => $ticket58Path,
        ]);
    }
}
