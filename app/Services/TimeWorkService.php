<?php

namespace App\Services;

use App\Http\Requests\RequestUpdateTimeWork;
use App\Repositories\HospitalDepartmentRepository;
use App\Repositories\HospitalServiceRepository;
use App\Repositories\InforDoctorRepository;
use App\Repositories\TimeWorkInterface;
use App\Repositories\WorkScheduleRepository;
use Throwable;

class TimeWorkService
{
    protected TimeWorkInterface $timeWorkRepository;

    public function __construct(TimeWorkInterface $timeWorkRepository)
    {
        $this->timeWorkRepository = $timeWorkRepository;
    }

    public function responseOK($status = 200, $data = null, $message = '')
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $status,
        ], $status);
    }

    public function responseError($status = 400, $message = '')
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
        ], $status);
    }

    public function edit(RequestUpdateTimeWork $request)
    {
        try {
            $user = auth()->guard('user_api')->user();
            $filter = (object) [
                'id_hospital' => $user->id,
            ];
            $timeWork = $this->timeWorkRepository->getTimeWork($filter)->first();
            $request->merge([
                'times' => json_encode($request->times),
            ]);
            $timeWork = $this->timeWorkRepository->updateTimeWork($timeWork, $request->all());
            $timeWork->times = json_decode($timeWork->times);

            return $this->responseOK(200, $timeWork, 'Chỉnh sửa lịch làm việc thành công !');
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function detail()
    {
        try {
            $user = auth()->guard('user_api')->user();
            $filter = (object) [
                'id_hospital' => $user->id,
            ];
            $timeWork = $this->timeWorkRepository->getTimeWork($filter)->first();
            $timeWork->times = json_decode($timeWork->times);

            return $this->responseOK(200, $timeWork, 'Xem chi tiết lịch làm việc thành công !');
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // chia nhỏ lịch làm việc ra
    public function divideTime($timeArray, $maxMinutes)
    {
        $start = strtotime($timeArray[0]);
        $end = strtotime($timeArray[1]);
        $dividedTimes = [];
        while ($start < $end) {
            $nextEnd = $start + ($maxMinutes * 60);
            if ($nextEnd > $end) {
                break; // Loại bỏ đoạn thời gian ngắn hơn $maxMinutes
            }
            $dividedTimes[] = [date('H:i', $start), date('H:i', $nextEnd)];
            $start = $nextEnd;
        }

        return $dividedTimes;
    }

    // loại bỏ đi các khoảng thời gian đã qua của ngày hiện tại
    public function filterTimes($divided_times)
    {
        $current_time = date('H:i');
        $new_divided_times = [];
        foreach ($divided_times as $key => $time_segment) {
            $start_time = $time_segment[0];
            if ($start_time >= $current_time) {
                $new_divided_times = array_slice($divided_times, $key);
                break;
            }
        }

        return $new_divided_times;
    }

    public function advise($id_doctor)
    {
        try {
            // infor doctor
            $inforDoctor = InforDoctorRepository::getInforDoctor(['id_doctor' => $id_doctor])->first();
            if (empty($inforDoctor)) {
                return $this->responseError(404, 'Không tìm thấy bác sĩ !');
            }

            // time_advise
            $hospitalDepartment = HospitalDepartmentRepository::searchHospitalDepartment([
                'id_department' => $inforDoctor->id_department,
                'id_hospital' => $inforDoctor->id_hospital,
            ])->first();

            // time_work
            $filter = (object) [
                'id_hospital' => $inforDoctor->id_hospital,
            ];
            $timeWork = $this->timeWorkRepository->getTimeWork($filter)->first();
            $timeWork->times = json_decode($timeWork->times);

            // loại bỏ các thứ trong quá khứ
            $currentDayName = strtolower(date('l'));
            $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            $currentDayIndex = array_search($currentDayName, $daysOfWeek);
            foreach ($daysOfWeek as $dayOfWeek) {
                if (array_search($dayOfWeek, $daysOfWeek) < $currentDayIndex) {
                    $timeWork->times->$dayOfWeek->enable = false;
                }
            }

            // gắn ngày vào cho các thứ
            $currentDate = now()->startOfWeek();
            foreach ($daysOfWeek as $dayOfWeek) {
                $timeWork->times->$dayOfWeek->date = $currentDate->format('Y-m-d');
                $currentDate->addDay(1);
            }

            // chia nhỏ lịch làm việc ra

            $maxMinutes = $hospitalDepartment->time_advise;
            foreach ($timeWork->times as $index => $day) {
                $day->morning->divided_times = $this->divideTime($day->morning->time, $maxMinutes);
                $day->afternoon->divided_times = $this->divideTime($day->afternoon->time, $maxMinutes);
                $day->night->divided_times = $this->divideTime($day->night->time, $maxMinutes);
            }
            // chia nhỏ lịch làm việc ra

            // loại bỏ đi các khoảng thời gian đã qua của ngày hiện tại

            $timeWork->times->$currentDayName->morning->divided_times = $this->filterTimes($timeWork->times->$currentDayName->morning->divided_times);
            $timeWork->times->$currentDayName->afternoon->divided_times = $this->filterTimes($timeWork->times->$currentDayName->afternoon->divided_times);
            $timeWork->times->$currentDayName->night->divided_times = $this->filterTimes($timeWork->times->$currentDayName->night->divided_times);

            // loại bỏ đi các ngày mà bác sĩ bận trong work_chedule
            foreach ($daysOfWeek as $dayOfWeek) {
                if ($timeWork->times->$dayOfWeek->enable == false) {
                    // $timeWork->times->$dayOfWeek = null; // bỏ ngày đó ra khỏi lịch luôn
                } else {
                    $time = [];
                    $time['date'] = $timeWork->times->$dayOfWeek->date;

                    // morning
                    $newDividedTimes = [];
                    foreach ($timeWork->times->$dayOfWeek->morning->divided_times as $index => $interval) {
                        $time['interval'] = $interval;
                        $filter = [
                            'time' => $time,
                            'id_doctor' => $id_doctor,
                            'id_service' => 'advise',
                        ];
                        $n = WorkScheduleRepository::getWorkSchedule($filter)->count();
                        if ($n == 0) {
                            $newDividedTimes[] = $interval;
                        }
                    }
                    $timeWork->times->$dayOfWeek->morning->divided_times = $newDividedTimes;

                    // afternoon
                    $newDividedTimes = [];
                    foreach ($timeWork->times->$dayOfWeek->afternoon->divided_times as $index => $interval) {
                        $time['interval'] = $interval;
                        $filter = [
                            'time' => $time,
                            'id_doctor' => $id_doctor,
                            'id_service' => 'advise',
                        ];
                        $n = WorkScheduleRepository::getWorkSchedule($filter)->count();
                        if ($n == 0) {
                            $newDividedTimes[] = $interval;
                        }
                    }
                    $timeWork->times->$dayOfWeek->afternoon->divided_times = $newDividedTimes;

                    // night
                    $newDividedTimes = [];
                    foreach ($timeWork->times->$dayOfWeek->night->divided_times as $index => $interval) {
                        $time['interval'] = $interval;
                        $filter = [
                            'time' => $time,
                            'id_doctor' => $id_doctor,
                            'id_service' => 'advise',
                        ];
                        $n = WorkScheduleRepository::getWorkSchedule($filter)->count();
                        if ($n == 0) {
                            $newDividedTimes[] = $interval;
                        }
                    }
                    $timeWork->times->$dayOfWeek->night->divided_times = $newDividedTimes;
                }
            }

            // BỔ SUNG : loại bỏ đi ngày (shift of = 4) hoặc các ca của mà bác sĩ xin nghỉ trong vacations chedule

            // bổ sung một số thông tin khác . space = còn chỗ của ngày đó
            foreach ($daysOfWeek as $dayOfWeek) {
                if ($timeWork->times->$dayOfWeek != null && $timeWork->times->$dayOfWeek->enable != false) {
                    $timeWork->times->$dayOfWeek->space =
                    count($timeWork->times->$dayOfWeek->morning->divided_times) +
                    count($timeWork->times->$dayOfWeek->afternoon->divided_times) +
                    count($timeWork->times->$dayOfWeek->night->divided_times);
                }
            }

            return $this->responseOK(200, $timeWork, 'Xem chi tiết lịch làm việc thành công !');
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function service($id_hospital_service)
    {
        try {
            // hospital services
            $filter = (object) [
                'id_hospital_services' => $id_hospital_service,
            ];
            $hospitalServices = HospitalServiceRepository::searchAll($filter)->first();
            if (empty($hospitalServices)) {
                return $this->responseError(404, 'Không tìm thấy dịch vụ trong bệnh viện !');
            }

            // time_work
            $filter = (object) [
                'id_hospital' => $hospitalServices->id_hospital,
            ];
            $timeWork = $this->timeWorkRepository->getTimeWork($filter)->first();
            $timeWork->times = json_decode($timeWork->times);

            // loại bỏ các thứ trong quá khứ
            $currentDayName = strtolower(date('l'));
            $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            $currentDayIndex = array_search($currentDayName, $daysOfWeek);
            foreach ($daysOfWeek as $dayOfWeek) {
                if (array_search($dayOfWeek, $daysOfWeek) < $currentDayIndex) {
                    $timeWork->times->$dayOfWeek->enable = false;
                }
            }

            // gắn ngày vào cho các thứ
            $currentDate = now()->startOfWeek();
            foreach ($daysOfWeek as $dayOfWeek) {
                $timeWork->times->$dayOfWeek->date = $currentDate->format('Y-m-d');
                $currentDate->addDay(1);
            }

            $maxMinutes = $hospitalServices->time_advise_hospital_service;
            foreach ($timeWork->times as $index => $day) {
                $day->morning->divided_times = $this->divideTime($day->morning->time, $maxMinutes);
                $day->afternoon->divided_times = $this->divideTime($day->afternoon->time, $maxMinutes);
                $day->night->divided_times = $this->divideTime($day->night->time, $maxMinutes);
            }
            // chia nhỏ lịch làm việc ra

            // loại bỏ đi các khoảng thời gian đã qua của ngày hiện tại

            $timeWork->times->$currentDayName->morning->divided_times = $this->filterTimes($timeWork->times->$currentDayName->morning->divided_times);
            $timeWork->times->$currentDayName->afternoon->divided_times = $this->filterTimes($timeWork->times->$currentDayName->afternoon->divided_times);
            $timeWork->times->$currentDayName->night->divided_times = $this->filterTimes($timeWork->times->$currentDayName->night->divided_times);

            // bổ sung một số thông tin khác . space = còn chỗ của ngày đó
            foreach ($daysOfWeek as $dayOfWeek) {
                if ($timeWork->times->$dayOfWeek != null) {
                    $timeWork->times->$dayOfWeek->space =
                    count($timeWork->times->$dayOfWeek->morning->divided_times) +
                    count($timeWork->times->$dayOfWeek->afternoon->divided_times) +
                    count($timeWork->times->$dayOfWeek->night->divided_times);
                }
            }

            foreach ($daysOfWeek as $dayOfWeek) {
                if ($timeWork->times->$dayOfWeek->enable == false) {
                    // $timeWork->times->$dayOfWeek = null;
                }
            }

            return $this->responseOK(200, $timeWork, 'Xem chi tiết lịch dịch vụ thành công !');
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
