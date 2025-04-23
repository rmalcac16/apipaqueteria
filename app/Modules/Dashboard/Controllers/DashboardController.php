<?php

namespace Modules\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use Modules\Dashboard\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    protected DashboardService $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function getKpis(): JsonResponse
    {
        return response()->json($this->service->getKpis());
    }

    public function pagosPorMetodoYAgencia(): JsonResponse
    {
        return response()->json($this->service->pagosPorMetodoYAgencia());
    }

    public function agenciaTop(): JsonResponse
    {
        return response()->json($this->service->agenciaTopRecaudadora());
    }

    public function getAlertas(): JsonResponse
    {
        return response()->json($this->service->getAlertas());
    }
}
