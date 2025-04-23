<?php

namespace Modules\Dashboard\Services;

use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getKpis(): array
    {
        return [
            'total_envios' => DB::table('envios')->count(),
            'envios_pendientes_asignar' => DB::table('envios')->whereNull('viaje_id')->count(),
            'total_viajes' => DB::table('viajes')->count(),
            'viajes_activos' => DB::table('viajes')->where('estado', 'en_transito')->count(),
            'total_ingresos_mes' => DB::table('pago_envios')
                ->whereMonth('created_at', now()->month)
                ->sum('monto'),
            'total_facturas' => DB::table('comprobantes')->where('tipoComprobante', '01')->count(),
            'total_boletas' => DB::table('comprobantes')->where('tipoComprobante', '03')->count(),
        ];
    }

    public function pagosPorMetodoYAgencia(): array
    {
        return DB::table('pago_envios')
            ->join('envios', 'pago_envios.envio_id', '=', 'envios.id')
            ->join('agencias', 'envios.agencia_origen_id', '=', 'agencias.id')
            ->select('agencias.nombre as agencia', 'pago_envios.forma_pago', DB::raw('SUM(pago_envios.monto) as total'))
            ->groupBy('agencias.nombre', 'pago_envios.forma_pago')
            ->get()
            ->groupBy('agencia')
            ->toArray();
    }

    public function agenciaTopRecaudadora(): array
    {
        return DB::table('pago_envios')
            ->join('envios', 'pago_envios.envio_id', '=', 'envios.id')
            ->join('agencias', 'envios.agencia_id', '=', 'agencias.id')
            ->select('agencias.nombre', DB::raw('SUM(pago_envios.monto) as total_recaudado'))
            ->groupBy('agencias.nombre')
            ->orderByDesc('total_recaudado')
            ->limit(1)
            ->first() ?? ['nombre' => null, 'total_recaudado' => 0];
    }

    public function getAlertas(): array
    {
        $enviosSinAsignar = DB::table('envios')
            ->whereNull('viaje_id')
            ->whereDate('created_at', '<=', now()->subDay())
            ->count();

        $viajesNoIniciados = DB::table('viajes')
            ->where('estado', 'programado')
            ->whereDate('fecha_salida', '<=', now()->subDay())
            ->count();

        return [
            'envios_sin_asignar_mayor_24h' => $enviosSinAsignar,
            'viajes_programados_sin_iniciar' => $viajesNoIniciados,
        ];
    }
}
