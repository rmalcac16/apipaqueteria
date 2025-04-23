<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Envio\Services\EnvioService;

class EnvioSeeder extends Seeder
{
    protected EnvioService $envioService;

    public function __construct(EnvioService $envioService)
    {
        $this->envioService = $envioService;
    }

    public function run(): void
    {
        $envios = [
            [
                'numeroOrden'        => 100001,
                'codigo'             => 'ENV-' . strtoupper(Str::random(6)),
                'fechaEmision'       => now(),
                'fechaTraslado'      => now()->addDays(1),
                'user_id'            => 1,
                'remitente_id'       => 1,
                'destinatario_id'    => 2,
                'pagador_id'         => 1,
                'viaje_id'           => 1,
                'agencia_origen_id'  => 1,
                'agencia_destino_id' => 2,
                'pesoTotal'          => 12.5,
                'unidadMedida'       => 'KGM',
                'observaciones'      => 'Envío de productos textiles',
                'recojoDomicilio'    => true,
                'recojo_direccion'   => 'Jr. Lima 123',
                'recojo_ubigeo'      => '150101',
                'recojo_telefono'    => '987654321',
                'entregaDomicilio'   => false,
                'items' => [
                    [
                        'cantidad'      => 3,
                        'unidad_medida' => 'CJ',
                        'codigo'        => 'PROD-001',
                        'descripcion'   => 'Cajas de ropa para bebé',
                    ],
                    [
                        'cantidad'      => 1,
                        'unidad_medida' => 'KGM',
                        'descripcion'   => 'Bolsa adicional de telas',
                    ],
                ]
            ],
            [
                'numeroOrden'        => 100002,
                'codigo'             => 'ENV-' . strtoupper(Str::random(6)),
                'fechaEmision'       => now(),
                'fechaTraslado'      => now()->addDays(2),
                'user_id'            => 1,
                'remitente_id'       => 3,
                'destinatario_id'    => 4,
                'pagador_id'         => 3,
                'viaje_id'           => 2,
                'agencia_origen_id'  => 2,
                'agencia_destino_id' => 3,
                'pesoTotal'          => 8.0,
                'unidadMedida'       => 'KGM',
                'observaciones'      => 'Documentos y suministros',
                'recojoDomicilio'    => false,
                'entregaDomicilio'   => true,
                'entrega_direccion'  => 'Av. Siempre Viva 742',
                'entrega_ubigeo'     => '150102',
                'entrega_telefono'   => '912345678',
                'items' => [
                    [
                        'cantidad'      => 10,
                        'unidad_medida' => 'UND',
                        'codigo'        => 'DOC-01',
                        'descripcion'   => 'Sobres sellados con documentación',
                    ],
                ]
            ],
            [
                'numeroOrden'        => 100003,
                'codigo'             => 'ENV-' . strtoupper(Str::random(6)),
                'fechaEmision'       => now(),
                'fechaTraslado'      => now()->addDays(1),
                'user_id'            => 1,
                'remitente_id'       => 2,
                'destinatario_id'    => 4,
                'pagador_id'         => 2,
                'viaje_id'           => 1,
                'agencia_origen_id'  => 1,
                'agencia_destino_id' => 3,
                'pesoTotal'          => 5.5,
                'unidadMedida'       => 'KGM',
                'observaciones'      => 'Herramientas de precisión',
                'recojoDomicilio'    => true,
                'recojo_direccion'   => 'Av. Central 500',
                'recojo_ubigeo'      => '150103',
                'recojo_telefono'    => '987000123',
                'entregaDomicilio'   => false,
                'items' => [
                    [
                        'cantidad'      => 2,
                        'unidad_medida' => 'CJ',
                        'descripcion'   => 'Juego de destornilladores industriales',
                    ],
                ],
                'documentos_sustento' => [
                    [
                        'tipo_documento' => '01',
                        'serie_numero'   => 'F001-456789',
                        'ruc_emisor'     => '20481234567',
                    ],
                ]
            ],
            [
                'numeroOrden'        => 100004,
                'codigo'             => 'ENV-' . strtoupper(Str::random(6)),
                'fechaEmision'       => now(),
                'fechaTraslado'      => now()->addDays(3),
                'user_id'            => 1,
                'remitente_id'       => 1,
                'destinatario_id'    => 3,
                'pagador_id'         => 1,
                'viaje_id'           => 2,
                'agencia_origen_id'  => 2,
                'agencia_destino_id' => 1,
                'pesoTotal'          => 15.0,
                'unidadMedida'       => 'KGM',
                'observaciones'      => 'Material de oficina',
                'recojoDomicilio'    => false,
                'entregaDomicilio'   => true,
                'entrega_direccion'  => 'Calle Comercio 111',
                'entrega_ubigeo'     => '150104',
                'entrega_telefono'   => '912000999',
                'items' => [
                    [
                        'cantidad'      => 5,
                        'unidad_medida' => 'CJ',
                        'descripcion'   => 'Cajas de papel bond tamaño A4',
                    ],
                ],
                'documentos_sustento' => [
                    [
                        'tipo_documento' => '09',
                        'serie_numero'   => 'T001-000987',
                        'ruc_emisor'     => '20556667777',
                    ],
                ]
            ],
        ];

        foreach ($envios as $envioData) {
            $this->envioService->create($envioData);
        }
    }
}
