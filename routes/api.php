<?php

use App\Events\AgenciasUpdated;
use App\Events\VehiculoActualizado;
use App\Events\VehiculosUpdated;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Middleware\CheckRole;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Modules\Auth\Controllers\AuthController;
use Modules\Agencia\Controllers\AgenciaController;
use Modules\Cliente\Controllers\ClienteController;
use Modules\Comprobante\Controllers\ComprobanteController;
use Modules\Dashboard\Controllers\DashboardController;
use Modules\Usuario\Controllers\UsuarioController;
use Modules\Vehiculo\Controllers\VehiculoController;
use Modules\Envio\Controllers\EnvioController;
use Modules\Viaje\Controllers\ViajeController;
use Modules\PagoEnvio\Controllers\PagoEnvioController;
use Modules\GuiaRemisionTransportista\Controllers\GuiaRemisionTransportistaController;
use Modules\SeguimientoEnvio\Controllers\SeguimientoEnvioController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::get('login', [AuthController::class, 'login']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->get('me', [AuthController::class, 'me']);
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum', CheckRole::class . ':admin'])->group(function () {
    Route::resource('agencias', AgenciaController::class)->except(['create', 'edit']);
    Route::resource('usuarios', UsuarioController::class)->except(['create', 'edit']);
    Route::resource('vehiculos', VehiculoController::class)->except(['create', 'edit']);
});

Route::middleware(['auth:sanctum', CheckRole::class . ':admin,agente'])->group(function () {

    Route::prefix('dashboard')->controller(DashboardController::class)->group(function () {
        Route::get('/kpis', 'getKpis');
        Route::get('/pagos-por-metodo-agencia', 'pagosPorMetodoYAgencia');
        Route::get('/agencia-top-recaudadora', 'agenciaTop');
        Route::get('/alertas', 'getAlertas');
    });


    Route::post('envios/buscarCodigo', [EnvioController::class, 'findByCodigo']);

    Route::prefix('envios/{envio}')->group(function () {
        Route::get('pdf-a4', [EnvioController::class, 'verPdfA4']);
        Route::get('ticket-80', [EnvioController::class, 'verTicket80']);
        Route::get('ticket-58', [EnvioController::class, 'verTicket58']);

        Route::post('pagar', [PagoEnvioController::class, 'pagarEnvio']);
        Route::post('cancelar', [EnvioController::class, 'cancelar']);
        Route::get('seguimientos', [SeguimientoEnvioController::class, 'index']);
        Route::post('seguimientos', [SeguimientoEnvioController::class, 'store']);
    });
    Route::resource('envios', EnvioController::class)->except(['create', 'edit']);

    // Route::post('viajes/{viaje}/asignar-envios', [ViajeController::class, 'asignarEnvios']);
    // Route::post('viajes/{viaje}/desasignar-envios', [ViajeController::class, 'desasignarEnvios']);

    Route::prefix('viajes')->controller(ViajeController::class)->group(function () {
        Route::patch('{viaje}/iniciar', 'iniciar');
        Route::patch('{viaje}/finalizar', 'finalizar');
        Route::patch('{viaje}/cancelar', 'cancelar');
        Route::patch('{viaje}/conductor', 'cambiarConductor');
        Route::patch('{viaje}/vehiculo', 'cambiarVehiculo');
        Route::post('{viaje}/asignar-envios',  'asignarEnvios');
        Route::post('{viaje}/desasignar-envios', 'desasignarEnvios');
        Route::get('{viaje}/envios-disponibles', 'enviosDisponibles');
    });
    Route::apiResource('viajes', ViajeController::class);


    Route::prefix('pago-envios/{pagoEnvio}')->group(function () {
        Route::post('pagar', [PagoEnvioController::class, 'pagar']);
    });

    Route::get('pago-envios/reporte', [PagoEnvioController::class, 'reporteConDatosYPdf']);
    Route::apiResource('pago-envios', PagoEnvioController::class);

    Route::apiResource('clientes', ClienteController::class);

    Route::post('guias-remision-transportista', [GuiaRemisionTransportistaController::class, 'store']);
    Route::post('comprobantes', [ComprobanteController::class, 'store']);

    Route::prefix('settings')->controller(SettingsController::class)->group(function () {
        Route::get('{key?}', 'show');
        Route::put('{key?}', 'update');
    });
});

Route::get('tracking', [EnvioController::class, 'trackingEnvio']);


Route::prefix('envios/{envio}')->group(function () {
    Route::get('pdf-a4', [EnvioController::class, 'verPdfA4']);
    Route::get('ticket-80', [EnvioController::class, 'verTicket80']);
    Route::get('ticket-58', [EnvioController::class, 'verTicket58']);
});


Route::prefix('guias-remision-transportista/{GuiaRemisionTransportista}')->group(function () {
    Route::get('pdf-a4', [GuiaRemisionTransportistaController::class, 'verPdfA4']);
    Route::get('ticket-80', [GuiaRemisionTransportistaController::class, 'verTicket80']);
    Route::get('ticket-58', [GuiaRemisionTransportistaController::class, 'verTicket58']);

    Route::get('enviarSunat', [GuiaRemisionTransportistaController::class, 'enviarSunat']);
});


Route::prefix('comprobantes/{comprobante}')->group(function () {
    Route::get('pdf-a4', [ComprobanteController::class, 'verPdfA4']);
    Route::get('ticket-80', [ComprobanteController::class, 'verTicket80']);
    Route::get('ticket-58', [ComprobanteController::class, 'verTicket58']);

    Route::get('enviarSunat', [ComprobanteController::class, 'enviarSunat']);
});


Route::get('/auth/login', function () {
    return response()->json(['message' => 'Login endpoint ready.'], 200);
});
