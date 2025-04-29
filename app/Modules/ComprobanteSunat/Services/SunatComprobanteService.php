<?php

namespace Modules\ComprobanteSunat\Services;

use DateTime;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Modules\ComprobanteSunat\Helpers\SunatConfigHelper;
use DOMDocument;
use DOMXPath;
use Greenter\Model\Sale\Cuota;
use Greenter\Model\Sale\FormaPagos\FormaPagoCredito;

class SunatComprobanteService
{
    public function emitirComprobante(array $data, $comprobante): array
    {
        $see = SunatConfigHelper::getSee();

        // CLIENTE
        $client = (new Client())
            ->setTipoDoc($data['cliente']['tipo_doc'])
            ->setNumDoc($data['cliente']['num_doc'])
            ->setRznSocial($data['cliente']['razon_social']);

        // EMPRESA EMISORA
        $address = (new Address())
            ->setUbigueo($data['empresa']['ubigeo'])
            ->setDepartamento($data['empresa']['departamento'])
            ->setProvincia($data['empresa']['provincia'])
            ->setDistrito($data['empresa']['distrito'])
            ->setDireccion($data['empresa']['direccion']);

        $company = (new Company())
            ->setRuc($data['empresa']['ruc'])
            ->setRazonSocial($data['empresa']['razon_social'])
            ->setNombreComercial($data['empresa']['nombre_comercial'])
            ->setAddress($address);

        // FACTURA o BOLETA
        $invoice = (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101') // Venta Interna
            ->setTipoDoc($data['tipo']) // 01: Factura, 03: Boleta
            ->setSerie($data['serie'])
            ->setCorrelativo($data['correlativo'])
            ->setFechaEmision(new DateTime())
            ->setFormaPago($data['forma_pago'] === 'contado' ? new FormaPagoContado() : new FormaPagoCredito($data['total']))
            ->setTipoMoneda('PEN')
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas($data['total_gravadas'])
            ->setMtoIGV($data['igv'])
            ->setTotalImpuestos($data['igv'])
            ->setValorVenta($data['total_gravadas'])
            ->setSubTotal($data['total'])
            ->setMtoImpVenta($data['total']);

        if ($data['forma_pago'] === 'credito') {

            $cuotas = [];

            foreach ($data['cuotas'] as $cuota) {
                $cuotas[] = (new Cuota())
                    ->setMonto($cuota['monto'])
                    ->setFechaPago(new DateTime($cuota['fecha_pago']));
            }

            $invoice->setCuotas($cuotas);
        }

        $items = [];

        foreach ($data['items'] as $item) {
            $items[] = (new SaleDetail())
                ->setCodProducto($item['codigo'])
                ->setUnidad($item['unidad'])
                ->setCantidad($item['cantidad'])
                ->setDescripcion($item['descripcion'])
                ->setMtoValorUnitario($item['valor_unitario'])
                ->setMtoBaseIgv($item['base_igv'])
                ->setPorcentajeIgv(18.00)
                ->setIgv($item['igv'])
                ->setTipAfeIgv('10') // Gravado
                ->setTotalImpuestos($item['igv'])
                ->setMtoValorVenta($item['valor_total'])
                ->setMtoPrecioUnitario($item['precio_unitario']);
        }

        $legend = (new Legend())
            ->setCode('1000')->setValue($data['leyenda']);

        $invoice->setDetails($items)
            ->setLegends([$legend]);

        // ENVÍO
        $result = $see->send($invoice);

        // Guardar XML y CDR
        $name = $invoice->getName();


        $xmlPath = "sunat/comprobantes/xml/{$name}.xml";
        $cdrPath = "sunat/comprobantes/cdr/R-{$name}.zip";


        if (!is_dir(storage_path('sunat/comprobantes/xml'))) {
            mkdir(storage_path('sunat/comprobantes/xml'), 0755, true);
        }

        $xmlContent = $see->getFactory()->getLastXml();

        file_put_contents(storage_path($xmlPath), $xmlContent);

        if (!$result->isSuccess()) {
            return [
                'success' => false,
                'error' => $result->getError()->getMessage(),
                'code' => $result->getError()->getCode()
            ];
        }

        // Si no existe la carpeta, crearla
        if (!is_dir(storage_path('sunat/comprobantes/cdr'))) {
            mkdir(storage_path('sunat/comprobantes/cdr'), 0755, true);
        }
        file_put_contents(storage_path($cdrPath), $result->getCdrZip());

        // Extraer hash del XML firmado
        $dom = new DOMDocument();
        $dom->loadXML($xmlContent);
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace("ds", "http://www.w3.org/2000/09/xmldsig#");
        $hashNode = $xpath->query('//ds:DigestValue')->item(0);
        $hash = $hashNode ? $hashNode->nodeValue : null;


        $xmlPath = "sunat/xml/{$name}.xml";
        $cdrPath = "sunat/cdr/R-{$name}.zip";

        $cdr = $result->getCdrResponse();
        $code = (int) $cdr->getCode();

        $status = '';
        $tipo = '';
        $notas = $cdr->getNotes();


        switch (true) {
            case $code === 0:
                $status = 'aceptado';
                $tipo = 'success';
                break;

            case $code >= 2000 && $code <= 3999:
                $status = 'rechazado';
                $tipo = 'error';
                break;

            case $code >= 100 && $code < 2000:
                $status = 'observado';
                $tipo = 'warning';
                break;

            default:
                $status = 'pendiente';
                $tipo = 'info';
                break;
        }


        $comprobante->update([
            'xml_path'         => $xmlPath,
            'cdr_path'         => $cdrPath,
            'estado_sunat'     => $status,
            'codigo_sunat'     => $code,
            'descripcion_sunat' => $cdr->getDescription(),
            'hash_code'         => $hash,
        ]);


        return [
            'success' => true,
            'nombre' => $invoice->getName(),
            'cdr' => [
                'code' => $code,
                'status' => $status,
                'tipo' => $tipo,
                'descripcion' => $cdr->getDescription(),
                'notas' => $notas,
            ]
        ];
    }
}
