<?php

namespace Database\Seeders;

use App\Models\Viaje;
use App\Models\Vehiculo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ViajeSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener tractocamiones y semirremolques
        $tractos = Vehiculo::where('tipo', 'tractocamion')->pluck('id')->toArray();
        $semis  = Vehiculo::where('tipo', 'semirremolque')->pluck('id')->toArray();

        // Agencias disponibles
        $agencias = [1, 2, 3];

        // Conductores
        $conductores = [5, 6];

        foreach (range(1, 5) as $i) {
            // Selección de agencias distintas
            $agenciaOrigen = $agencias[array_rand($agencias)];
            do {
                $agenciaDestino = $agencias[array_rand($agencias)];
            } while ($agenciaDestino === $agenciaOrigen);

            // Vehículos
            $vehiculoPrincipal = $tractos[array_rand($tractos)];
            $vehiculoSecundario = rand(0, 1) ? $semis[array_rand($semis)] : null;

            // Conductores
            $conductorPrincipal = $conductores[array_rand($conductores)];
            $conductorSecundario = rand(0, 1) ? $conductores[array_rand($conductores)] : null;
            if ($conductorSecundario === $conductorPrincipal) {
                $conductorSecundario = null;
            }

            Viaje::create([
                'codigo'                  => 'VJ-' . strtoupper(Str::random(6)),
                'user_id'                 => 1,
                'vehiculo_principal_id'   => $vehiculoPrincipal,
                'vehiculo_secundario_id'  => $vehiculoSecundario,
                'conductor_principal_id'  => $conductorPrincipal,
                'conductor_secundario_id' => $conductorSecundario,
                'agencia_origen_id'       => $agenciaOrigen,
                'agencia_destino_id'      => $agenciaDestino,
                'fecha_salida'            => now()->addDays($i),
                'fecha_llegada'          => now()->addDays($i + 1),
                'estado'                  => 'programado',
            ]);
        }
    }
}
