<?php

namespace Modules\Viaje\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Viaje;
use Illuminate\Http\Request;
use Modules\Envio\Services\EnvioService;
use Modules\Viaje\Requests\StoreViajeRequest;
use Modules\Viaje\Requests\UpdateViajeRequest;
use Modules\Viaje\Services\ViajeService;

class ViajeController extends Controller
{
    public function __construct(protected ViajeService $service) {}

    /**
     * Listar viajes con filtros.
     */
    public function index(Request $request)
    {
        return response()->json($this->service->buscar($request->all()));
    }

    /**
     * Mostrar un viaje por ID.
     */
    public function show(int $id)
    {
        return response()->json($this->service->ver($id));
    }

    /**
     * Crear nuevo viaje (solo admin).
     */
    public function store(StoreViajeRequest $request)
    {
        $viaje = $this->service->create($request->validated());
        return response()->json($viaje, 201);
    }

    /**
     * Actualizar un viaje.
     */
    public function update(UpdateViajeRequest $request, Viaje $viaje)
    {
        $updated = $this->service->update($viaje, $request->validated());
        return response()->json($updated);
    }

    /**
     * Cambiar conductor del viaje.
     */
    public function cambiarConductor(Request $request, Viaje $viaje)
    {
        $request->validate(['conductor_id' => 'required|exists:users,id']);
        return response()->json($this->service->cambiarConductor($viaje, $request->conductor_id));
    }

    /**
     * Cambiar vehículo del viaje.
     */
    public function cambiarVehiculo(Request $request, Viaje $viaje)
    {
        $request->validate(['vehiculo_id' => 'required|exists:vehiculos,id']);
        return response()->json($this->service->cambiarVehiculo($viaje, $request->vehiculo_id));
    }

    /**
     * Iniciar el viaje.
     */
    public function iniciar(Viaje $viaje)
    {
        return response()->json($this->service->iniciar($viaje));
    }

    /**
     * Finalizar el viaje.
     */
    public function finalizar(Viaje $viaje)
    {
        return response()->json($this->service->finalizar($viaje));
    }

    /**
     * Cancelar el viaje.
     */
    public function cancelar(Request $request, Viaje $viaje)
    {
        return response()->json(
            $this->service->cancelar($viaje, $request->input('motivo'))
        );
    }

    /**
     * Eliminar un viaje.
     */
    public function destroy(Viaje $viaje)
    {
        $this->service->delete($viaje);
        return response()->json(['message' => 'Viaje eliminado correctamente.']);
    }


    public function enviosDisponibles(Viaje $viaje)
    {
        $envios = \App\Models\Envio::query()
            ->with([
                'remitente:id,nombre_completo',
                'destinatario:id,nombre_completo',
                'seguimiento'
            ])
            ->whereNull('viaje_id')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($envios);
    }



    public function asignarEnvios(Request $request, Viaje $viaje)
    {
        $request->validate([
            'envios' => 'required|array|min:1',
            'envios.*' => 'exists:envios,id',
        ]);

        $this->service->asignarEnvios($viaje, $request->envios);

        return response()->json(['message' => 'Envíos asignados correctamente.']);
    }

    public function desasignarEnvios(Request $request, Viaje $viaje)
    {
        $request->validate([
            'envios' => 'required|array|min:1',
            'envios.*' => 'exists:envios,id',
        ]);

        $this->service->desasignarEnvios($viaje, $request->envios);

        return response()->json(['message' => 'Envíos desasignados correctamente.']);
    }
}
