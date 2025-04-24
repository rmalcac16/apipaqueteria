<?php

namespace Modules\Comprobante\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Modules\Comprobante\Services\ComprobanteService;

class ComprobanteController extends Controller
{
    protected ComprobanteService $service;

    public function __construct(ComprobanteService $service)
    {
        $this->service = $service;
    }

    public function verPdfA4(int $id): Response
    {
        $comprobante = $this->service->find($id);

        $pdf = Pdf::loadView('pdf.comprobante.a4', compact('comprobante'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream("comprobante-{$comprobante->serie}-{$comprobante->numero}-A4.pdf");
    }

    public function verTicket80(int $id): Response
    {
        $comprobante = $this->service->find($id);

        $pdf = Pdf::loadView('pdf.comprobante.ticket_80', compact('comprobante'))
            ->setPaper([0, 0, 226.77, 600], 'portrait');

        return $pdf->stream("comprobante-{$comprobante->serie}-{$comprobante->numero}-ticket80.pdf");
    }

    public function verTicket58(int $id): Response
    {
        $comprobante = $this->service->find($id);

        $pdf = Pdf::loadView('pdf.comprobante.ticket_58', compact('comprobante'))
            ->setPaper([0, 0, 164.41, 700], 'portrait');

        return $pdf->stream("comprobante-{$comprobante->serie}-{$comprobante->numero}-ticket58.pdf");
    }

    public function enviarSunat(int $id)
    {
        $comprobante = $this->service->find($id);
        return $this->service->enviarSunat($comprobante);
    }
}
