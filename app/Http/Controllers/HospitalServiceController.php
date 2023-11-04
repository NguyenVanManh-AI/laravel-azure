<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestCreateHospitalService;
use App\Http\Requests\RequestStatusService;
use App\Http\Requests\RequestUpdateHospitalService;
use App\Services\HospitalServiceService;
use Illuminate\Http\Request;

class HospitalServiceController extends Controller
{
    protected HospitalServiceService $hospitalServiceService;

    public function __construct(HospitalServiceService $hospitalServiceService)
    {
        $this->hospitalServiceService = $hospitalServiceService;
    }

    public function add(RequestCreateHospitalService $request)
    {
        return $this->hospitalServiceService->add($request);
    }

    public function edit(RequestUpdateHospitalService $request, $id)
    {
        return $this->hospitalServiceService->edit($request, $id);
    }

    public function changeStatus(RequestStatusService $request)
    {
        return $this->hospitalServiceService->changeStatus($request);
    }

    public function hospitalManage(Request $request)
    {
        return $this->hospitalServiceService->hospitalManage($request);
    }

    public function detailManage(Request $request, $id)
    {
        return $this->hospitalServiceService->detailManage($request, $id);
    }

    public function serviceOfHospital(Request $request, $id)
    {
        return $this->hospitalServiceService->serviceOfHospital($request, $id);
    }

    public function serviceOfHospitalSelect(Request $request, $id)
    {
        return $this->hospitalServiceService->serviceOfHospitalSelect($request, $id);
    }

    public function details(Request $request, $id)
    {
        return $this->hospitalServiceService->details($request, $id);
    }

    public function moreRating(Request $request, $id_service)
    {
        return $this->hospitalServiceService->moreRating($request, $id_service);
    }

    public function all(Request $request)
    {
        return $this->hospitalServiceService->all($request);
    }
}
