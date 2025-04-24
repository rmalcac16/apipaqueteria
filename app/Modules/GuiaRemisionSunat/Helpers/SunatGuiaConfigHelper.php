<?php

namespace Modules\GuiaRemisionSunat\Helpers;

use Exception;
use Greenter\Api;

class SunatGuiaConfigHelper
{
    public static function getSeeApi(): Api
    {
        $api = new Api([
            'auth' => env('SUNAT_GUIA_AUTH_URL', 'https://gre-test.nubefact.com/v1'),
            'cpe' => env('SUNAT_GUIA_CPE_URL', 'https://gre-test.nubefact.com/v1'),
        ]);

        $certificate = file_get_contents(__DIR__ . '/../Resources/Certificados/cert.pem');

        if ($certificate === false) {
            throw new Exception('No se pudo cargar el certificado de guía');
        }

        return $api->setBuilderOptions([
            'strict_variables' => true,
            'optimizations' => 0,
            'debug' => true,
            'cache' => false,
        ])
            ->setApiCredentials(
                env('SUNAT_GUIA_CLIENT_ID', 'test-85e5b0ae-255c-4891-a595-0b98c65c9854'),
                env('SUNAT_GUIA_CLIENT_SECRET', 'test-Hty/M6QshYvPgItX2P0+Kw==')
            )
            ->setClaveSOL(
                env('SUNAT_GUIA_RUC', '20161515648'),
                env('SUNAT_GUIA_USUARIO', 'MODDATOS'),
                env('SUNAT_GUIA_CLAVE', 'MODDATOS')
            )
            ->setCertificate($certificate);
    }
}
