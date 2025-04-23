<?php

namespace Modules\GuiaRemisionTransportista\Controllers;

use App\Http\Controllers\Controller;
use Modules\GuiaRemisionTransportista\Services\GuiaRemisionTransportistaService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class GuiaRemisionTransportistaController extends Controller
{
    protected GuiaRemisionTransportistaService $service;

    public function __construct(GuiaRemisionTransportistaService $service)
    {
        $this->service = $service;
    }

    public function verPdfA4(int $id): Response
    {
        $guia = $this->service->find($id);
        $pdf = Pdf::loadView('pdf.guia_remision.a4', compact('guia'))->setPaper('A4', 'portrait');
        return $pdf->stream("guia-{$guia->codigo}-A4.pdf");
    }

    public function verTicket80(int $id): Response
    {
        $guia = $this->service->find($id);
        $pdf = Pdf::loadView('pdf.guia_remision.ticket_80', compact('guia'))
            ->setPaper([0, 0, 226.77, 700], 'portrait');
        return $pdf->stream("guia-{$guia->codigo}-ticket80.pdf");
    }

    public function verTicket58(int $id): Response
    {
        $guia = $this->service->find($id);
        $pdf = Pdf::loadView('pdf.guia_remision.ticket_58', compact('guia'))
            ->setPaper([0, 0, 164.41, 700], 'portrait');
        return $pdf->stream("guia-{$guia->codigo}-ticket58.pdf");
    }
}
