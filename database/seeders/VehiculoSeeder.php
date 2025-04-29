<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehiculo;

class VehiculoSeeder extends Seeder
{
    public function run(): void
    {
        // Tractocamiones
        foreach (range(1, 1) as $i) {
            Vehiculo::create([
                'tipo'         => 'tractocamion',
                'placa'        => 'TRC' . $this->generateAlphanumeric(3 + $i % 2), // Total de 6 a 8 caracteres
                'tuc'          => "TUCTRC{$i}",
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
        foreach (range(1, 1) as $i) {
            Vehiculo::create([
                'tipo'         => 'semirremolque',
                'placa'        => 'SMR' . $this->generateAlphanumeric(3 + $i % 2), // Total de 6 a 8 caracteres
                'tuc'          => "TUC-SMR{$i}",
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

    private function generateAlphanumeric($length = 6): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $result;
    }
}
