<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SettingsSeeder::class,
            AdminUserSeeder::class,
            AgenciaSeeder::class,
            UsuarioSeeder::class,
            ClienteSeeder::class,
            VehiculoSeeder::class,
            //ViajeSeeder::class,
            // EnvioSeeder::class,
            //PagoEnvioSeeder::class,
        ]);
    }
}
