<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SerieComprobante;

class SerieComprobanteSeeder extends Seeder
{
    public function run(): void
    {
        SerieComprobante::insert([
            [
                'tipo_comprobante' => 'factura',
                'serie' => 'F001',
                'descripcion' => 'Factura general',
                'estado' => 'activa',
                'sunat_origen' => 'SEE-del-contribuyente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_comprobante' => 'boleta',
                'serie' => 'B001',
                'descripcion' => 'Boleta estándar',
                'estado' => 'activa',
                'sunat_origen' => 'SEE-del-contribuyente',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
