<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('agencias')->insert([
            [
                'nombre' => 'Cosio',
                'codigo' => 'V01',
                'direccion' => 'Ignacio Cossio 1505',
                'correo' => null,
                'telefono' => null,
                'ubigeo' => '140136',
                'distrito' => 'SAN JUAN DE MIRAFLORES',
                'provincia' => 'LIMA',
                'departamento' => 'LIMA',
                'estado' => true,
                'googleMapsUrl' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Humboldt',
                'codigo' => 'V02',
                'direccion' => 'Jirón Alexander Von Humboldt 606',
                'correo' => null,
                'telefono' => null,
                'ubigeo' => '140109',
                'distrito' => 'LA VICTORIA',
                'provincia' => 'LIMA',
                'departamento' => 'LIMA',
                'estado' => true,
                'googleMapsUrl' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Cajamarca',
                'codigo' => 'V03',
                'direccion' => 'Av. La Paz 1282, Cajamarca',
                'correo' => null,
                'telefono' => null,
                'ubigeo' => '060101',
                'distrito' => 'CAJAMARCA',
                'provincia' => 'CAJAMARCA',
                'departamento' => 'CAJAMARCA',
                'estado' => true,
                'googleMapsUrl' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
