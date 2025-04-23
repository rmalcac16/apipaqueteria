<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Models\Vehiculo;

class VehiculoSeeder extends Seeder
{
    public function run(): void
    {
        if (!in_array(App::environment(), ['local', 'dev'])) {
            $this->command->warn('VehiculoSeeder no se ejecutó (no es entorno local/dev)');
            return;
        }

        // Tractocamiones
        foreach (range(1, 5) as $i) {
            Vehiculo::create([
                'tipo'         => 'tractocamion',
                'placa'        => "TRC-10{$i}{$i}",
                'tuc'          => "TUC-TRC-00{$i}",
                'marca'        => 'Volvo',
                'modelo'       => 'FH16',
                'anio'         => 2018 + $i,
                'capacidadKg'  => 32000 + ($i * 1000),
                'volumenM3'    => 80 + $i,
                'estado'       => true,
                'acopladoA_id' => null,
            ]);
        }

        // Semirremolques
        foreach (range(1, 5) as $i) {
            Vehiculo::create([
                'tipo'         => 'semirremolque',
                'placa'        => "SMR-20{$i}{$i}",
                'tuc'          => "TUC-SMR-00{$i}",
                'marca'        => 'Randon',
                'modelo'       => 'SR45',
                'anio'         => 2016 + $i,
                'capacidadKg'  => 28000 + ($i * 800),
                'volumenM3'    => 60 + ($i * 2),
                'estado'       => true,
                'acopladoA_id' => null,
            ]);
        }
    }
}
