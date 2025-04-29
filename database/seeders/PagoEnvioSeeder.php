<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PagoEnvio;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Agencia;
use Modules\Comprobante\Services\ComprobanteService;

class PagoEnvioSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $clienteBoleta = Cliente::where('tipoDocumento', "1")->first();
        $clienteFactura = Cliente::where('tipoDocumento', "6")->first();

        $agencia = Agencia::first();

        $comprobanteService = app(ComprobanteService::class);

        // 🚚 Envío 1 - contado
        $pago1 = PagoEnvio::create([
            'envio_id'           => 1,
            'monto'              => 850.00,
            'estado'             => 'completado',
            'metodo_pago'         => 'efectivo',
            'medio_pago'         => 'yape',
            'numero_transaccion' => 'YAPE001',
            'fecha_pago'         => now(),
            'realizado_por'      => $clienteBoleta->id,
            'cobrado_por'        => $user->id,
            'agencia_id'         => $agencia->id,
            'observaciones'      => 'Pago contado de envío 1',
        ]);

        $comprobanteService->crearDesdePago($pago1, [
            'tipo'        => '03', // boleta
            'forma_pago'  => 'contado',
        ]);

        // 🚚 Envío 2 - crédito en 2 cuotas
        $pago2 = PagoEnvio::create([
            'envio_id'           => 2,
            'monto'              => 1000.00,
            'estado'             => 'pendiente',
            'metodo_pago'         => 'transferencia',
            'medio_pago'         => 'bcp',
            'numero_transaccion' => 'BCP2024',
            'fecha_pago'         => now(),
            'realizado_por'      => $clienteFactura->id,
            'cobrado_por'        => $user->id,
            'agencia_id'         => $agencia->id,
            'observaciones'      => 'Pago crédito de envío 2',
        ]);

        $comprobanteService->crearDesdePago($pago2, [
            'tipo'        => '01',
            'forma_pago'  => 'credito',
            'cuotas' => [
                ['monto' => 500, 'fecha_vencimiento' => now()->addDays(15)->toDateString()],
                ['monto' => 500, 'fecha_vencimiento' => now()->addDays(30)->toDateString()],
            ],
        ]);
    }
}
