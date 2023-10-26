<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestCreateHealthInsuranceHospital;
use App\Services\HealthInsuranceHospitalService;
use Illuminate\Http\Request;

class HealthInsuranceHospitalController extends Controller
{
    protected HealthInsuranceHospitalService $healInsurHosService;

    public function __construct(HealthInsuranceHospitalService $healInsurHosService)
    {
        $this->healInsurHosService = $healInsurHosService;
    }

    public function add(RequestCreateHealthInsuranceHospital $request)
    {
        return $this->healInsurHosService->add($request);
    }

    public function delete($id)
    {
        return $this->healInsurHosService->delete($id);
    }

    public function ofHospital(Request $request, $id)
    {
        return $this->healInsurHosService->ofHospital($request, $id);
    }

    public function details($id)
    {
        return $this->healInsurHosService->details($id);
    }
}
