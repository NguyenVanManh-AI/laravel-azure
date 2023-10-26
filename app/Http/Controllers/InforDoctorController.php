<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestUpdateDoctor;
use App\Services\InforDoctorService;
use Illuminate\Http\Request;

class InforDoctorController extends Controller
{
    protected InforDoctorService $inforDoctorService;

    public function __construct(InforDoctorService $inforDoctorService)
    {
        $this->inforDoctorService = $inforDoctorService;
    }

    public function profile()
    {
        return $this->inforDoctorService->profile();
    }

    public function viewProfile(Request $request, $id)
    {
        return $this->inforDoctorService->viewProfile($request, $id);
    }

    public function updateProfile(RequestUpdateDoctor $request)
    {
        return $this->inforDoctorService->updateProfile($request);
    }

    public function bookDoctor(Request $request, $id_hospital, $id_department)
    {
        return $this->inforDoctorService->bookDoctor($request, $id_hospital, $id_department);
    }
}
