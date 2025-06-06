<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Configuración de emisión de comprobantes
            'COMPROBANTE_SUNAT_AUTO' => 1,
            'GUIA_REMISION_SUNAT_AUTO' => 1,

            // Configuración de la empresa
            'BUSINESS_RUC' => '20515963406',
            'BUSINESS_NAME' => 'POPEYE CARGOS SAC',
            'BUSINESS_NAME_COMMERCIAL' => 'POPEYE CARGOS',
            'BUSINESS_ADDRESS' => 'CAL. IGNACIO COSSIO 1505',
            'BUSINESS_UBIGEO' => '150101',
            'BUSINESS_DEPARTAMENTO' => 'LIMA',
            'BUSINESS_PROVINCIA' => 'LIMA',
            'BUSINESS_DISTRITO' => 'LA VICTORIA',
            'BUSINESS_PHONE' => '+51 988 069 143',
            'BUSINESS_URL' => 'www.popeyecargo.com',
            'BUSINESS_EMAIL' => 'logistica@popeyecargo.com',
            'BUSINESS_MTC' => '0000000001',

            // SUNAT FACTURACION
            'SUNAT_FACTURACION_RUC' => '20000000001',
            'SUNAT_FACTURACION_USUARIO' => 'MODDATOS',
            'SUNAT_FACTURACION_CLAVE' => 'MODDATOS',
            'SUNAT_FACTURACION_CERTIFICADO' => public_path('certificados/certificate.pem'),
            'SUNAT_FACTURACION_ENTORNO' => 'beta',

            // SUNAT GUIA DE REMISION
            'SUNAT_GUIA_AUTH_URL' => 'https://gre-test.nubefact.com/v1',
            'SUNAT_GUIA_CPE_URL' => 'https://gre-test.nubefact.com/v1',
            'SUNAT_GUIA_CLIENT_ID' => 'test-85e5b0ae-255c-4891-a595-0b98c65c9854',
            'SUNAT_GUIA_CLIENT_SECRET' => 'test-Hty/M6QshYvPgItX2P0+Kw==',
            'SUNAT_GUIA_RUC' => '20161515648',
            'SUNAT_GUIA_USUARIO' => 'MODDATOS',
            'SUNAT_GUIA_CLAVE' => 'MODDATOS',
            'SUNAT_GUIA_CERTIFICADO' => public_path('certificados/certificate.pem'),
            'SUNAT_GUIA_ENTORNO' => 'beta',

            // SERIES INICIALES
            'SERIE_FACTURA' => 'F101',
            'SERIE_BOLETA' => 'B101',
            'SERIE_GUIA_REMITENTE' => 'T101',
            'SERIE_GUIA_TRANSPORTISTA' => 'V101',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
