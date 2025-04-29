<?php

namespace Modules\Envio\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Modules\Envio\Services\EnvioService;
use Modules\Envio\Requests\StoreEnvioRequest;
use Modules\Envio\Requests\UpdateEnvioRequest;

class EnvioController extends Controller
{
    protected EnvioService $envioService;

    public function __construct(EnvioService $envioService)
    {
        $this->envioService = $envioService;
    }

    public function index(): JsonResponse
    {
        return response()->json($this->envioService->getAll());
    }

    public function store(StoreEnvioRequest $request): JsonResponse
    {
        return response()->json($this->envioService->create($request->validated()), 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->envioService->find($id));
    }

    public function update(UpdateEnvioRequest $request, int $id): JsonResponse
    {
        return response()->json($this->envioService->update($id, $request->validated()));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->envioService->delete($id);
        return response()->json(null, 204);
    }


    public function verPdfA4(int $id): Response
    {
        $envio = $this->envioService->find($id);

        $pdf = Pdf::loadView('pdf.envio.a4', compact('envio'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream("envio-{$envio->codigo}-A4.pdf");
    }
    public function verTicket80(int $id): Response
    {
        $envio = $this->envioService->find($id);

        $pdf = Pdf::loadView('pdf.envio.ticket_80', compact('envio'))
            ->setPaper([0, 0, 226.77, 750], 'portrait');

        return $pdf->stream("envio-{$envio->codigo}-ticket80.pdf");
    }
    public function verTicket58(int $id): Response
    {
        $envio = $this->envioService->find($id);

        $pdf = Pdf::loadView('pdf.envio.ticket_58', compact('envio'))
            ->setPaper([0, 0, 164.41, 600], 'portrait');

        return $pdf->stream("envio-{$envio->codigo}-ticket58.pdf");
    }
}
