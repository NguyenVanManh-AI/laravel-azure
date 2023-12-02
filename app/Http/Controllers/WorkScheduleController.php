<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestConfirmWorkSchedule;
use App\Http\Requests\RequestCreateWorkScheduleAdvise;
use App\Http\Requests\RequestCreateWorkScheduleService;
use App\Http\Requests\RequestUpdateInforPatient;
use App\Services\WorkScheduleService;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    protected WorkScheduleService $workScheduleService;

    public function __construct(WorkScheduleService $workScheduleService)
    {
        $this->workScheduleService = $workScheduleService;
    }

    public function addAdvise(RequestCreateWorkScheduleAdvise $request)
    {
        return $this->workScheduleService->addAdvise($request);
    }

    public function addService(RequestCreateWorkScheduleService $request)
    {
        return $this->workScheduleService->addService($request);
    }

    public function hospitalWorkSchedule(Request $request)
    {
        return $this->workScheduleService->hospitalWorkSchedule($request);
    }

    public function listSpecifyDoctor(Request $request, $id_work_schedule)
    {
        return $this->workScheduleService->listSpecifyDoctor($request, $id_work_schedule);
    }

    public function specifyDoctor(Request $request)
    {
        return $this->workScheduleService->specifyDoctor($request);
    }

    public function doctorWorkSchedule(Request $request)
    {
        return $this->workScheduleService->doctorWorkSchedule($request);
    }

    public function userWorkSchedule(Request $request)
    {
        return $this->workScheduleService->userWorkSchedule($request);
    }

    public function userCancel(Request $request, $id_work_schedule)
    {
        return $this->workScheduleService->userCancel($request, $id_work_schedule);
    }

    public function hospitalCancel(Request $request, $id_work_schedule)
    {
        return $this->workScheduleService->hospitalCancel($request, $id_work_schedule);
    }

    public function changeConfirm(RequestConfirmWorkSchedule $request, $id_work_schedule)
    {
        return $this->workScheduleService->changeConfirm($request, $id_work_schedule);
    }

    public function userCancelMany(Request $request)
    {
        return $this->workScheduleService->userCancelMany($request);
    }

    public function hospitalCancelMany(Request $request)
    {
        return $this->workScheduleService->hospitalCancelMany($request);
    }

    public function updateInforPatient(RequestUpdateInforPatient $request, $id_work_schedule)
    {
        return $this->workScheduleService->updateInforPatient($request, $id_work_schedule);
    }
}
