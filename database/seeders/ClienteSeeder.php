<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('clientes')->insert([
            [
                'tipoDocumento'    => 'B',
                'numeroDocumento'  => '999000111',
                'nombreCompleto'   => 'Andrés Ramírez',
                'direccion'        => 'Mz. H Lt. 10, Urb. Sol de Oro',
                'telefono'         => '+51 912345678',
                'correo'           => 'andres.ramirez@example.com',
                'frecuente'        => false,
                'observaciones'    => 'Solicitó información sobre promociones',
            ],
            [
                'tipoDocumento'    => 'F',
                'numeroDocumento'  => '20600533445',
                'nombreCompleto'   => 'Corporación Soluciones SAC',
                'direccion'        => 'Av. La Marina 1234, San Miguel',
                'telefono'         => '+51 913222333',
                'correo'           => 'contacto@solucionessac.com',
                'frecuente'        => true,
                'observaciones'    => 'Cliente frecuente con crédito mensual',
            ],
            [
                'tipoDocumento'    => 'B',
                'numeroDocumento'  => '998877665',
                'nombreCompleto'   => 'Rocío Luján',
                'direccion'        => 'Jr. Amazonas 456, Trujillo',
                'telefono'         => '+51 914567890',
                'correo'           => 'rocio.lujan@example.com',
                'frecuente'        => true,
                'observaciones'    => 'Solicita recojo a domicilio',
            ],
            [
                'tipoDocumento'    => 'F',
                'numeroDocumento'  => '20111222334',
                'nombreCompleto'   => 'Inversiones Andinas EIRL',
                'direccion'        => 'Parque Industrial N° 30, Arequipa',
                'telefono'         => '+51 915888777',
                'correo'           => 'ventas@andinas.com.pe',
                'frecuente'        => false,
                'observaciones'    => 'Primer contacto vía web',
            ],
            [
                'tipoDocumento'    => 'B',
                'numeroDocumento'  => '977665544',
                'nombreCompleto'   => 'Luis Moreno',
                'direccion'        => 'Av. Grau 789, Piura',
                'telefono'         => '+51 916666999',
                'correo'           => 'luis.moreno@example.com',
                'frecuente'        => true,
                'observaciones'    => 'Solicitó cotización para encomienda mensual',
            ],
        ]);
    }
}
