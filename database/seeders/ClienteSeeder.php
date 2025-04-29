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
                'tipoDocumento'    => '1',
                'numeroDocumento'  => '00000000',
                'nombreCompleto'   => 'Consumidor Final',
                'direccion'        => 'No especificada',
                'telefono'         => '+51 999999999',
                'correo'           => 'noreply@popeyecargo.com',
                'frecuente'        => false,
                'observaciones'    => 'Cliente sin registro',
            ]
        ]);
    }
}
