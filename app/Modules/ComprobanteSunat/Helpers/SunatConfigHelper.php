<?php

namespace Modules\ComprobanteSunat\Helpers;

use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;

class SunatConfigHelper
{
    public static function getSee(): See
    {
        $see = new See();

        $see->setCertificate(
            file_get_contents(__DIR__ . '/../Resources/Certificados/certificate.pem')
        );

        $see->setService(SunatEndpoints::FE_BETA); // o SunatEndpoints::FE_PRODUCCION

        $see->setClaveSOL(
            getSetting('SUNAT_FACTURACION_RUC', '20000000001'),
            getSetting('SUNAT_FACTURACION_USUARIO', 'MODDATOS'),
            getSetting('SUNAT_FACTURACION_CLAVE', 'moddatos')
        );

        return $see;
    }
}
