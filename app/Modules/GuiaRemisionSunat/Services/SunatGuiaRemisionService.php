<?php

namespace Modules\GuiaRemisionSunat\Services;

use DateTime;
use DOMDocument;
use DOMXPath;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Despatch\Despatch;
use Greenter\Model\Despatch\DespatchDetail;
use Greenter\Model\Despatch\Direction;
use Greenter\Model\Despatch\Driver;
use Greenter\Model\Despatch\Shipment;
use Greenter\Model\Despatch\Transportist;
use Greenter\Model\Despatch\Vehicle;
use Greenter\Model\DocumentInterface;
use Greenter\XMLSecLibs\Sunat\SignedXml;
use Modules\GuiaRemisionSunat\Helpers\SunatGuiaConfigHelper;
use Twig\Environment;

class SunatGuiaRemisionService
{
    public function emitirGuia(array $data, $guia): array
    {

        // VEHÍCULO
        $vehiculoPrincipal = (new Vehicle())
            ->setPlaca($data['vehiculo']['placa']);

        // CHOFER
        $chofer = (new Driver())
            ->setTipo('Principal')
            ->setTipoDoc('1')
            ->setNroDoc($data['chofer']['dni'])
            ->setLicencia($data['chofer']['licencia'])
            ->setNombres($data['chofer']['nombres'])
            ->setApellidos($data['chofer']['apellidos']);


        $envio = new Shipment();

        $envio
            ->setFecTraslado(new DateTime($data['envio']['fecha_traslado']))
            ->setPesoTotal((float)$data['envio']['peso_total'])
            ->setUndPesoTotal($data['envio']['unidad_medida'])
            ->setVehiculo($vehiculoPrincipal)
            ->setChoferes([$chofer])
            ->setLlegada(new Direction($data['envio']['ubigeo_origen'], $data['envio']['direccion_origen']))
            ->setPartida(new Direction($data['envio']['ubigeo_destino'], $data['envio']['direccion_destino']))
            ->setTransportista(
                (new Transportist())
                    ->setNumDoc($data['empresa']['ruc'])
                    ->setRznSocial($data['empresa']['razon_social'])
                    ->setNroMtc($data['empresa']['nro_mtc'])
            );

        // EMPRESA
        $company = (new Company())
            ->setRuc($data['empresa']['ruc'])
            ->setRazonSocial($data['empresa']['razon_social']);

        // DESTINATARIO
        $destinatario = (new Client())
            ->setTipoDoc($data['destinatario']['tipo_doc'])
            ->setNumDoc($data['destinatario']['numero_doc'])
            ->setRznSocial($data['destinatario']['razon_social']);

        // REMITENTE (Tercero)
        $remitente = (new Client())
            ->setTipoDoc($data['remitente']['tipo_doc'])
            ->setNumDoc($data['remitente']['numero_doc'])
            ->setRznSocial($data['remitente']['razon_social']);


        $despatch = new Despatch();

        $despatch->setVersion('2022')
            ->setTipoDoc('31')
            ->setSerie($data['serie'])
            ->setCorrelativo($data['correlativo'])
            ->setFechaEmision(new DateTime())
            ->setCompany($company)
            ->setDestinatario($destinatario)
            ->setTercero($remitente)
            ->setEnvio($envio);

        $items = [];

        foreach ($data['items'] as $item) {

            $items[] = (new DespatchDetail())
                ->setCantidad($item['cantidad'])
                ->setUnidad($item['unidad'])
                ->setDescripcion($item['descripcion'])
                ->setCodigo($item['codigo'] ?? null);
        }

        $despatch->setDetails($items);


        $twig = app(Environment::class);
        $xmlCustom = $twig->render('despatch2022', [
            'doc' => $despatch,
        ]);

        // ENVÍO A SUNAT
        $api = SunatGuiaConfigHelper::getSeeApi();

        $xmlFirmado = SunatGuiaConfigHelper::signXml($xmlCustom);

        $res = $api->sendXml($despatch->getName() . '.xml', $xmlFirmado);

        $name = $despatch->getName();

        $xmlPath = "sunat/guias_remision/xml/{$name}.xml";
        $cdrPath = "sunat/guias_remision/cdr/R-{$name}.zip";

        if (!is_dir(storage_path('sunat/guias_remision/xml'))) {
            mkdir(storage_path('sunat/guias_remision/xml'), 0755, true);
        }

        file_put_contents(storage_path($xmlPath), $xmlFirmado);

        if (!$res->isSuccess()) {
            return [
                'success' => false,
                'error' => $res->getError()->getMessage(),
                'code' => $res->getError()->getCode(),
            ];
        }

        $ticket = $res->getTicket();

        $res = $api->getStatus($ticket);

        if (!$res->isSuccess()) {
            return [
                'success' => false,
                'error' => $res->getError()->getMessage(),
                'code' => $res->getError()->getCode(),
            ];
        }

        $cdr = $res->getCdrResponse();

        if (!is_dir(storage_path('sunat/guias_remision/cdr'))) {
            mkdir(storage_path('sunat/guias_remision/cdr'), 0755, true);
        }

        file_put_contents(storage_path($cdrPath), $res->getCdrZip());


        // Extraer hash del XML firmado
        $dom = new DOMDocument();
        $dom->loadXML($xmlFirmado);
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace("ds", "http://www.w3.org/2000/09/xmldsig#");
        $hashNode = $xpath->query('//ds:DigestValue')->item(0);
        $hash = $hashNode ? $hashNode->nodeValue : null;

        // ESTADO
        $cdr = $res->getCdrResponse();
        $code = (int) $cdr->getCode();

        $status = match (true) {
            $code === 0 => 'aceptado',
            $code >= 2000 && $code <= 3999 => 'rechazado',
            $code >= 100 && $code < 2000 => 'observado',
            default => 'pendiente',
        };

        $tipo = match (true) {
            $status === 'aceptado' => 'success',
            $status === 'rechazado' => 'error',
            $status === 'observado' => 'warning',
            default => 'info',
        };

        // ACTUALIZAR MODELO
        $guia->update([
            'xml_path'         => $xmlPath,
            'cdr_path'         => $cdrPath,
            'estado_sunat'     => $status,
            'codigo_sunat'     => $code,
            'descripcion_sunat' => $cdr->getDescription(),
            'hash_code'         => $hash,
            'ticket'           => $ticket,
        ]);

        return [
            'success' => true,
            'nombre' => $name,
            'cdr' => [
                'code' => $code,
                'status' => $status,
                'tipo' => $tipo,
                'descripcion' => $cdr->getDescription(),
                'notas' => $cdr->getNotes(),
            ]
        ];
    }
}
