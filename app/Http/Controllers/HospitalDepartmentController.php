<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestCreateHospitalDepartment;
use App\Http\Requests\RequestUpdateHospitalDepartment;
use App\Services\HospitalDepartmentService;
use Illuminate\Http\Request;

class HospitalDepartmentController extends Controller
{
    protected HospitalDepartmentService $hospitalDepartmentService;

    public function __construct(HospitalDepartmentService $hospitalDepartmentService)
    {
        $this->hospitalDepartmentService = $hospitalDepartmentService;
    }

    public function add(RequestCreateHospitalDepartment $request)
    {
        return $this->hospitalDepartmentService->add($request);
    }

    public function edit(RequestUpdateHospitalDepartment $request, $id)
    {
        return $this->hospitalDepartmentService->edit($request, $id);
    }

    public function delete($id)
    {
        return $this->hospitalDepartmentService->delete($id);
    }

    public function departmentOfHospital(Request $request, $id)
    {
        return $this->hospitalDepartmentService->departmentOfHospital($request, $id);
    }

    public function details($id)
    {
        return $this->hospitalDepartmentService->details($id);
    }
}
