<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        if (!in_array(App::environment(), ['local', 'env'])) {
            $this->command->warn('UsuarioSeeder no se ejecutó (no es entorno local/env)');
            return;
        }

        // Agentes
        User::insert([
            // Agentes con agencia
            [
                'name'              => 'CarlosA',
                'nombreCompleto'    => 'Carlos Álvarez',
                'numeroDocumento'   => '22345678', // cambiado
                'email'             => 'carlos.alvarez@example.com',
                'telefono'          => '987111222',
                'rol'               => 'agente',
                'estado'            => true,
                'agencia_id'        => 1,
                'password'          => Hash::make('12345678'),
            ],
            [
                'name'              => 'LuciaM',
                'nombreCompleto'    => 'Lucía Martínez',
                'numeroDocumento'   => '87654321',
                'email'             => 'lucia.martinez@example.com',
                'telefono'          => '987333444',
                'rol'               => 'agente',
                'estado'            => true,
                'agencia_id'        => 2,
                'password'          => Hash::make('12345678'),
            ],
            [
                'name'              => 'FernandoG',
                'nombreCompleto'    => 'Fernando Gómez',
                'numeroDocumento'   => '11223344',
                'email'             => 'fernando.gomez@example.com',
                'telefono'          => '987555666',
                'rol'               => 'agente',
                'estado'            => true,
                'agencia_id'        => 3,
                'password'          => Hash::make('12345678'),
            ],
        ]);


        // Conductores
        User::insert([
            [
                'name'              => 'LuisR',
                'nombreCompleto'    => 'Luis Ramírez',
                'numeroDocumento'   => '44556677',
                'numeroLicencia'    => 'LIC-987654',
                'email'             => 'luis.ramirez@example.com',
                'telefono'          => '987777888',
                'rol'               => 'conductor',
                'estado'            => true,
                'agencia_id'        => null,
                'password'          => Hash::make('12345678'),
            ],
            [
                'name'              => 'SoniaT',
                'nombreCompleto'    => 'Sonia Torres',
                'numeroDocumento'   => '99887766',
                'numeroLicencia'    => 'LIC-123321',
                'email'             => 'sonia.torres@example.com',
                'telefono'          => '987999000',
                'rol'               => 'conductor',
                'estado'            => true,
                'agencia_id'        => null,
                'password'          => Hash::make('12345678'),
            ],
        ]);
    }
}
