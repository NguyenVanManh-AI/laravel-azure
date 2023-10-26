<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestCreateHealthInsurance;
use App\Http\Requests\RequestUpdateHealthInsurance;
use App\Services\HealthInsuranceService;
use Illuminate\Http\Request;

class HealthInsuranceController extends Controller
{
    protected HealthInsuranceService $healthInsuranceService;

    public function __construct(HealthInsuranceService $healthInsuranceService)
    {
        $this->healthInsuranceService = $healthInsuranceService;
    }

    public function add(RequestCreateHealthInsurance $request)
    {
        return $this->healthInsuranceService->add($request);
    }

    public function edit(RequestUpdateHealthInsurance $request, $id)
    {
        return $this->healthInsuranceService->edit($request, $id);
    }

    public function delete($id)
    {
        return $this->healthInsuranceService->delete($id);
    }

    public function all(Request $request)
    {
        return $this->healthInsuranceService->all($request);
    }

    public function details(Request $request, $id)
    {
        return $this->healthInsuranceService->details($request, $id);
    }
}
