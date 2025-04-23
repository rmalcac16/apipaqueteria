<?php

namespace Modules\GuiaRemisionTransportista\Services;

use App\Models\Envio;
use App\Models\GuiaRemisionTransportista;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class GuiaRemisionTransportistaService
{
    public function crearDesdeEnvio(Envio $envio, array $datos)
    {
        $viaje = $envio->viaje;

        if (!$viaje) {
            throw new \Exception('El envío no tiene un viaje asignado.');
        }

        $vehiculo = $viaje->vehiculo;
        $conductor = $viaje->conductor;

        $guia = GuiaRemisionTransportista::create([
            'envio_id' => $envio->id,
            'conductor_nombre' => $conductor->nombreCompleto,
            'conductor_tipo_documento' => $conductor->tipo_documento,
            'conductor_documento' => $conductor->numero_documento,
            'conductor_licencia' => $conductor->licencia,
            'vehiculo_placa' => $vehiculo->placa,
            'vehiculo_tuc' => $vehiculo->tuc,
            'vehiculo_certificado' => $vehiculo->certificado_inscripcion,
            'fecha_inicio_traslado' => $datos['fecha_inicio_traslado'],
            'modo_transporte' => '01',

            'punto_partida_ubigeo' => $viaje->ubigeo_salida,
            'punto_partida_direccion' => $viaje->direccion_salida,
            'punto_llegada_ubigeo' => $viaje->ubigeo_llegada,
            'punto_llegada_direccion' => $viaje->direccion_llegada,

            'descripcion_carga' => $envio->descripcion,
            'peso_total' => $envio->peso_kg,
            'unidad_medida' => $envio->unidad_medida ?? 'KGM',

            'remitente_nombre' => $envio->remitente->nombreCompleto,
            'remitente_documento' => $envio->remitente->numero_documento,
            'destinatario_nombre' => $envio->destinatario_nombre,
            'destinatario_documento' => $envio->destinatario_doc,

            'pagador_tipo' => $datos['pagador_tipo'],
            'pagador_tipo_documento' => $datos['pagador_tipo'] === 'tercero' ? $datos['pagador_tipo_documento'] : null,
            'pagador_numero_documento' => $datos['pagador_tipo'] === 'tercero' ? $datos['pagador_numero_documento'] : null,
            'pagador_nombre_razon_social' => $datos['pagador_tipo'] === 'tercero' ? $datos['pagador_nombre_razon_social'] : null,
        ]);


        if (!empty($datos['documentos_sustento'])) {
            foreach ($datos['documentos_sustento'] as $doc) {
                $guia->documentosSustento()->create([
                    'tipo_documento' => $doc['tipo_documento'],
                    'serie_numero' => $doc['serie_numero'],
                    'ruc_emisor' => $doc['ruc_emisor'],
                ]);
            }
        }


        //Creamos las carpetas necesarias para guardar los PDFs
        Storage::makeDirectory('guias', 0755, true);

        // PDF A4
        $pdfA4 = Pdf::loadView('pdf.guia-remision-a4', compact('guia'))->output();
        $filenameA4 = 'guias/guia_' . $guia->id . '_a4.pdf';
        Storage::disk('public')->put($filenameA4, $pdfA4);

        // PDF Ticket 80mm (226.77 puntos de ancho = 80mm)
        $pdfTicket = Pdf::loadView('pdf.guia-remision-ticket-80', compact('guia'))
            ->setPaper([0, 0, 226.77, 600], 'portrait')
            ->output();
        $filenameTicket = 'guias/guia_' . $guia->id . '_ticket.pdf';
        Storage::disk('public')->put($filenameTicket, $pdfTicket);


        $pdfTicket58 = Pdf::loadView('pdf.guia-remision-ticket-58', compact('guia'))
            ->setPaper([0, 0, 164.4, 600], 'portrait') // 58mm = 164.4pt
            ->output();
        $filenameTicket58 = 'guias/guia_' . $guia->id . '_ticket_58.pdf';
        Storage::disk('public')->put('guias/guia_' . $guia->id . '_ticket58.pdf', $pdfTicket58);

        // Guardar solo una ruta (opcional: podrías guardar ambas)
        $guia->update(['pdf_path' => $filenameA4]);

        return [
            'guia' => $guia,
            'pdf_urls' => [
                'a4' => asset('storage/' . $filenameA4),
                'ticket' => asset('storage/' . $filenameTicket),
                'ticket_58' => asset('storage/' . $filenameTicket58),
            ],
        ];
    }
}
