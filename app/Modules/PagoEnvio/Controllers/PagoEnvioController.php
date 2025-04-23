<?php

namespace Modules\PagoEnvio\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PagoEnvio;
use Illuminate\Http\JsonResponse;
use Modules\PagoEnvio\Requests\StorePagoEnvioRequest;
use Modules\PagoEnvio\Requests\UpdatePagoEnvioRequest;
use Modules\PagoEnvio\Services\PagoEnvioService;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PagoEnvioController extends Controller
{
    public function __construct(protected PagoEnvioService $service) {}

    public function index(): JsonResponse
    {
        return response()->json($this->service->all());
    }

    public function show($id): JsonResponse
    {
        return response()->json($this->service->find($id));
    }

    public function store(StorePagoEnvioRequest $request): JsonResponse
    {
        $pago = $this->service->create($request->validated());
        return response()->json($pago, 201);
    }

    public function update(UpdatePagoEnvioRequest $request, PagoEnvio $pagoEnvio): JsonResponse
    {
        $pago = $this->service->update($pagoEnvio, $request->validated());
        return response()->json($pago);
    }

    public function destroy(PagoEnvio $pagoEnvio): JsonResponse
    {
        $this->service->delete($pagoEnvio);
        return response()->json(['message' => 'Pago eliminado correctamente']);
    }

    public function reporteConDatosYPdf(Request $request)
    {
        $data = $this->service->reporteDinamico(
            agenciaId: $request->input('agencia_id'),
            fechaInicio: $request->input('fecha_inicio'),
            fechaFin: $request->input('fecha_fin'),
            metodoPago: $request->input('metodo_pago'),
            usuarioId: $request->input('usuario_id')
        );

        $nombreAgencia = preg_replace('/\s+/', '_', strtolower($data['filtros']['agencia']));
        $nombreMetodo = preg_replace('/\s+/', '_', strtolower($data['filtros']['metodo_pago']));
        $nombreUsuario = preg_replace('/\s+/', '_', strtolower($data['filtros']['usuario']));

        $filename = "reporte_pagos_{$nombreAgencia}_{$nombreMetodo}_{$nombreUsuario}_" . now()->format('Ymd_His') . '.pdf';

        $pdf = Pdf::loadView('pdf.reporte-pagos-dinamico', $data);
        if (!file_exists(storage_path('app/public/reportes/pagos'))) {
            mkdir(storage_path('app/public/reportes/pagos'), 0777, true);
        }

        $pdf->save(storage_path("app/public/reportes/pagos/{$filename}"));

        return response()->json([
            'status' => 'success',
            'filtros' => $data['filtros'],
            'total_pagado' => $data['total'],
            'data' => $data['pagos'],
            'pdf_url' =>  asset("storage/reportes/pagos/{$filename}"),
            'pdf_filename' => $filename,
        ]);
    }


    public function cancelarPago(PagoEnvio $pagoEnvio): JsonResponse
    {
        $pago = $this->service->cancelarPago($pagoEnvio);
        return response()->json($pago);
    }


    public function pagar(Request $request, PagoEnvio $pagoEnvio): JsonResponse
    {
        $validated =  $request->validate([
            'forma_pago' => 'required|in:efectivo,transferencia,deposito',
            'medio_pago' => 'nullable|in:yape,plin,bcp,interbank,bbva,otros',
            'numero_transaccion' => 'nullable|string|max:100|required_if:forma_pago,transferencia,deposito',
            'observaciones' => 'nullable|string|max:500',
            'tipo_comprobante' => 'nullable|in:01,03',
        ]);

        $pago = $this->service->pagarEnvio($pagoEnvio, $validated);
        return response()->json($pago);
    }
}
