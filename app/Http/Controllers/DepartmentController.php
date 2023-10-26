<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestCreateDepartment;
use App\Http\Requests\RequestUpdateDepartment;
use App\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    protected DepartmentService $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function add(RequestCreateDepartment $request)
    {
        return $this->departmentService->add($request);
    }

    public function edit(RequestUpdateDepartment $request, $id)
    {
        return $this->departmentService->edit($request, $id);
    }

    public function delete($id)
    {
        return $this->departmentService->delete($id);
    }

    public function all(Request $request)
    {
        return $this->departmentService->all($request);
    }

    public function details(Request $request, $id)
    {
        return $this->departmentService->details($request, $id);
    }
}
