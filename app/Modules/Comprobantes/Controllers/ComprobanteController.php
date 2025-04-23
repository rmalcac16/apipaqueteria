<?php

namespace Modules\Comprobantes\Controllers;

use App\Http\Controllers\Controller;
use Modules\Comprobantes\Requests\StoreComprobanteRequest;
use Modules\Comprobantes\Services\ComprobanteService;

class ComprobanteController extends Controller
{
    protected $service;

    public function __construct(ComprobanteService $service)
    {
        $this->service = $service;
    }

    public function store(StoreComprobanteRequest $request)
    {
        $comprobante = $this->service->crear($request->validated());

        return response()->json([
            'message' => 'Comprobante generado correctamente.',
            'data' => $comprobante,
            'pdf_urls' => [
                'a4' => asset('storage/' . $comprobante->pdf_path_a4),
                'ticket_80' => asset('storage/' . $comprobante->pdf_path_ticket_80),
                'ticket_58' => asset('storage/' . $comprobante->pdf_path_ticket_58),
            ]
        ]);
    }
}
