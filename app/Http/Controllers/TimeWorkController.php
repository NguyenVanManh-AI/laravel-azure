<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestUpdateTimeWork;
use App\Services\TimeWorkService;

class TimeWorkController extends Controller
{
    protected TimeWorkService $timeWorkService;

    public function __construct(TimeWorkService $timeWorkService)
    {
        $this->timeWorkService = $timeWorkService;
    }

    public function edit(RequestUpdateTimeWork $request)
    {
        return $this->timeWorkService->edit($request);
    }

    public function detail()
    {
        return $this->timeWorkService->detail();
    }

    public function advise($id_doctor)
    {
        return $this->timeWorkService->advise($id_doctor);
    }

    public function service($id_hospital_service)
    {
        return $this->timeWorkService->service($id_hospital_service);
    }
}
