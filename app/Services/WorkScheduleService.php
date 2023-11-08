<?php

namespace App\Services;

use App\Http\Requests\RequestCreateWorkScheduleAdvise;
use App\Http\Requests\RequestCreateWorkScheduleService;
use App\Jobs\SendMailNotify;
use App\Models\InforDoctor;
use App\Models\Rating;
use App\Models\User;
use App\Repositories\HospitalServiceRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkScheduleInterface;
use App\Repositories\WorkScheduleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;
use Throwable;

class WorkScheduleService
{
    protected WorkScheduleInterface $workScheduleRepository;

    public function __construct(
        WorkScheduleInterface $workScheduleRepository
    ) {
        $this->workScheduleRepository = $workScheduleRepository;
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

    public function addAdvise(RequestCreateWorkScheduleAdvise $request)
    {
        try {
            $user = Auth::user();
            $doctor = UserRepository::doctorOfHospital(['id_doctor' => $request->id_doctor])->first();
            if (empty($doctor)) {
                return $this->responseError(400, 'Không tìm thấy bác sĩ !');
            }
            $hospital = UserRepository::findUserById($doctor->id_hospital);

            $time = (object) $request->time;
            $id_doctor = $request->id_doctor;
            $filter = (object) [
                'id_doctors' => [$id_doctor],
                'time' => $time,
            ];
            $busy = $this->workScheduleRepository->getWorkSchedule($filter)->count();
            if ($busy > 0) {
                return $this->responseError(400, 'Rất tiếc , bác sĩ bận tại khung giờ này !');
            }

            $startTime = $time->interval[0];
            $endTime = $time->interval[1];
            $date = $time->date;

            $content = "Bạn có lịch tư vấn với bác sĩ $doctor->name_doctor thuộc chuyên khoa " .
            " $doctor->name_department của bệnh viện $hospital->name vào khoản thời gian từ lúc $startTime cho đến " .
            " $endTime của ngày $date tại địa chỉ $hospital->address.  SĐT Liên hệ bệnh viện : $hospital->phone .";

            $data = [
                'id_user' => $user->id,
                'id_doctor' => $request->id_doctor,
                'id_service' => null,
                'price' => $doctor->price,
                'time' => json_encode($time),
                'content' => $content,
            ];
            $workSchedule = WorkScheduleRepository::createWorkSchedule($data);
            $workSchedule->time = json_decode($workSchedule->time);

            Queue::push(new SendMailNotify($user->email, $content));

            return $this->responseOK(201, $workSchedule, 'Đặt lịch tư vấn thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }
    // sdt hospital chắc chắn có vì khi register là require

    public function addService(RequestCreateWorkScheduleService $request)
    {
        try {
            $filter = (object) [
                'id_hospital_services' => $request->id_hospital_service,
                'is_delete' => 0,
            ];
            $hospitalServices = HospitalServiceRepository::searchAll($filter)->first();
            if (empty($hospitalServices)) {
                return $this->responseError(400, 'Không tìm thấy dịch vụ trong bệnh viện !');
            }
            // all doctor of Department of Hospital of Service Hospital
            // lấy ra danh sách id tất cả các bác sĩ của chuyên khoa chứa dịch vụ đó
            $filter = (object) [
                'role' => 'doctor',
                'is_accept' => 1,
                'is_confirm' => 1,
                'id_department' => $hospitalServices->id_department,
                'id_hospital' => $hospitalServices->id_hospital,
            ];
            $allDoctor = UserRepository::doctorOfHospital($filter)->get();
            if (count($allDoctor) == 0) {
                return $this->responseError(400, 'Rất tiếc ! . Hiện tại chuyên khoa chứa dịch vụ này không có bác sĩ !');
            }

            $hospital = UserRepository::findUserById($hospitalServices->id_hospital);
            $user = Auth::user();
            $time = $request->time;
            $startTime = $time['interval'][0];
            $endTime = $time['interval'][1];
            $date = $time['date'];

            $content = "Bạn có lịch dịch vụ $hospitalServices->name thuộc chuyên khoa $hospitalServices->name_department của bệnh viện $hospital->name "
            . "vào khoản thời gian từ lúc $startTime cho đến " .
            " $endTime của ngày $date tại địa chỉ $hospital->address.  SĐT Liên hệ bệnh viện : $hospital->phone .";

            $data = [
                'id_user' => $user->id,
                'id_doctor' => null,
                'id_service' => $request->id_hospital_service,
                'price' => $hospitalServices->price_hospital_service,
                'time' => json_encode($time),
                'content' => $content,
            ];
            $workSchedule = WorkScheduleRepository::createWorkSchedule($data);
            $workSchedule->time = json_decode($workSchedule->time);

            Queue::push(new SendMailNotify($user->email, $content));

            return $this->responseOK(201, $workSchedule, 'Đặt lịch dịch vụ thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function listSpecifyDoctor(Request $request, $id_work_schedule)
    {
        try {
            $hospital = Auth::user();
            $filter = (object) [
                'search' => '',
                'hospital_id' => $hospital->id,
                'work_schedule_id' => $id_work_schedule,
                'is_service' => 'service',
                'role' => 'hospital',
            ];
            $workSchedule = $this->workScheduleRepository->searchWorkSchedule($filter)->first();
            if (empty($workSchedule)) {
                return $this->responseError(400, 'Không tìm thấy dịch vụ !');
            }

            if ($workSchedule->doctor_id != null) {
                return $this->responseError(400, 'Dịch vụ này đã có bác sĩ thực hiện !');
            } else {
                $time = json_decode($workSchedule->work_schedule_time);
                $filter = (object) [
                    'search' => '',
                    'role' => 'doctor',
                    'is_accept' => 1,
                    'is_confirm' => 1,
                    'id_department' => $workSchedule->department_id,
                    'id_hospital' => $hospital->id,
                ];
                $id_doctors = UserRepository::doctorOfHospital($filter)->get()->pluck('id_doctor')->toArray();

                $filter = (object) [
                    'id_doctors' => $id_doctors,
                    'time' => $time,
                ];
                $busy_doctors = array_unique($this->workScheduleRepository->getWorkSchedule($filter)->get()->pluck('id_doctor')->toArray());

                $free_time_doctors = array_diff($id_doctors, $busy_doctors);
                if (empty($free_time_doctors)) {
                    return $this->responseOK(200, null, 'Tất cả các bác sĩ đều bận trong khung giờ này !');
                }

                $listDoctors = User::whereIn('id', $free_time_doctors)->get();
                foreach ($listDoctors as $doctor) {
                    $doctor->id_doctor = $doctor->id;
                }
                $start = $time->interval[0];
                $end = $time->interval[1];

                return $this->responseOK(200, $listDoctors, "Lấy danh sách bác sĩ rảnh trong khung giờ $start đến $end ngày $time->date thành công !");
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function specifyDoctor(Request $request)
    {
        try {
            $id_work_schedule = $request->id_work_schedule;
            $id_doctor = $request->id_doctor;

            $hospital = Auth::user();
            $filter = (object) [
                'search' => '',
                'hospital_id' => $hospital->id,
                'work_schedule_id' => $id_work_schedule,
                'role' => 'hospital',
            ];
            $workSchedule = $this->workScheduleRepository->searchWorkSchedule($filter)->first();
            if (empty($workSchedule)) {
                return $this->responseError(400, 'Không tìm thấy lịch tư vấn hay dịch vụ !');
            }

            if ($workSchedule->doctor_id != null) {
                return $this->responseError(400, 'Dịch vụ này đã có bác sĩ thực hiện !');
            }

            $doctor = InforDoctor::where('id_doctor', $id_doctor)->first();
            if (empty($doctor)) {
                return $this->responseError(400, 'Không tìm thấy bác sĩ !');
            }

            if ($doctor->id_hospital != $hospital->id) {
                return $this->responseError(400, 'Bác sĩ không thuộc bệnh viện này !');
            }

            if ($doctor->id_department != $workSchedule->department_id) {
                return $this->responseError(400, 'Bác sĩ không nằm trong chuyên khoa của dịch vụ !');
            }

            $time = json_decode($workSchedule->work_schedule_time);
            $filter = (object) [
                'id_doctors' => [$id_doctor],
                'time' => $time,
            ];
            $busy = $this->workScheduleRepository->getWorkSchedule($filter)->count();
            if ($busy > 0) {
                return $this->responseError(400, 'Bác sĩ bận tại khung giờ này !');
            }

            $workSchedule->update([
                'id_doctor' => $id_doctor,
            ]);

            return $this->responseOK(200, $workSchedule, 'Chỉ định bác sĩ thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function hospitalWorkSchedule(Request $request)
    {
        try {
            $user = auth('user_api')->user();

            $orderBy = $request->typesort ?? 'work_schedules.id';
            switch ($orderBy) {
                case 'name':
                    $orderBy = 'hospital_services.name';
                    break;

                case 'price':
                    $orderBy = 'work_schedules.price';
                    break;

                case 'time':
                    $orderBy = 'time->date';
                    break;

                case 'latest':
                    $orderBy = 'work_schedules.id';
                    break;

                default:
                    $orderBy = 'work_schedules.id';
                    break;
            }

            $orderDirection = $request->sortlatest ?? 'true';
            switch ($orderDirection) {
                case 'true':
                    $orderDirection = 'DESC';
                    break;

                default:
                    $orderDirection = 'ASC';
                    break;
            }

            $filter = (object) [
                'search' => $request->search ?? '',
                'department_name' => $request->department_name ?? '',
                'hospital_id' => $user->id,
                'doctor_id' => $request->doctor_id ?? null,
                'is_service' => $request->is_service ?? '',
                'start_date' => $request->start_date ?? '',
                'end_date' => $request->end_date ?? '',
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
                'status' => $request->status ?? '',
                'role' => 'hospital',
            ];

            if (!(empty($request->paginate))) {
                $workSchedules = $this->workScheduleRepository->searchWorkSchedule($filter)->paginate($request->paginate);
            } else {
                $workSchedules = $this->workScheduleRepository->searchWorkSchedule($filter)->get();
            }

            foreach ($workSchedules as $workSchedule) {
                $workSchedule->work_schedule_time = json_decode($workSchedule->work_schedule_time);
                $workSchedule->hospital_infrastructure = json_decode($workSchedule->hospital_infrastructure);
                $workSchedule->service_infor = json_decode($workSchedule->service_infor);
            }

            return $this->responseOK(200, $workSchedules, 'Xem tất cả lịch tư vấn và dịch vụ thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function doctorWorkSchedule(Request $request)
    {
        try {
            $user = auth('user_api')->user();
            $infor_doctor = InforDoctor::where('id_doctor', $user->id)->first();

            $orderBy = $request->typesort ?? 'work_schedules.id';
            switch ($orderBy) {
                case 'name':
                    $orderBy = 'hospital_services.name';
                    break;

                case 'price':
                    $orderBy = 'work_schedules.price';
                    break;

                case 'time':
                    $orderBy = 'time->date';
                    break;

                case 'latest':
                    $orderBy = 'work_schedules.id';
                    break;

                default:
                    $orderBy = 'work_schedules.id';
                    break;
            }

            $orderDirection = $request->sortlatest ?? 'true';
            switch ($orderDirection) {
                case 'true':
                    $orderDirection = 'DESC';
                    break;

                default:
                    $orderDirection = 'ASC';
                    break;
            }

            $filter = (object) [
                'search' => $request->search ?? '',
                'doctor_id' => $user->id,
                'hospital_id' => $infor_doctor->id_hospital,
                'is_service' => $request->is_service ?? '',
                'start_date' => $request->start_date ?? '',
                'end_date' => $request->end_date ?? '',
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
                'status' => $request->status ?? '',
                'role' => 'doctor',
            ];

            if (!(empty($request->paginate))) {
                $workSchedules = $this->workScheduleRepository->searchWorkSchedule($filter)->paginate($request->paginate);
            } else {
                $workSchedules = $this->workScheduleRepository->searchWorkSchedule($filter)->get();
            }

            foreach ($workSchedules as $workSchedule) {
                $workSchedule->work_schedule_time = json_decode($workSchedule->work_schedule_time);
                $workSchedule->hospital_infrastructure = json_decode($workSchedule->hospital_infrastructure);
                $workSchedule->service_infor = json_decode($workSchedule->service_infor);
            }

            return $this->responseOK(200, $workSchedules, 'Xem tất cả lịch tư vấn và dịch vụ thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function userWorkSchedule(Request $request)
    {
        try {
            $user = auth('user_api')->user();

            $orderBy = $request->typesort ?? 'work_schedules.id';
            switch ($orderBy) {
                case 'name':
                    $orderBy = 'hospital_services.name';
                    break;

                case 'price':
                    $orderBy = 'work_schedules.price';
                    break;

                case 'time':
                    $orderBy = 'time->date';
                    break;

                case 'latest':
                    $orderBy = 'work_schedules.id';
                    break;

                default:
                    $orderBy = 'work_schedules.id';
                    break;
            }

            $orderDirection = $request->sortlatest ?? 'true';
            switch ($orderDirection) {
                case 'true':
                    $orderDirection = 'DESC';
                    break;

                default:
                    $orderDirection = 'ASC';
                    break;
            }

            $filter = (object) [
                'search' => $request->search ?? '',
                'user_id' => $user->id,
                'is_service' => $request->is_service ?? '',
                'start_date' => $request->start_date ?? '',
                'end_date' => $request->end_date ?? '',
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
                'status' => $request->status ?? '',
                'role' => 'user',
            ];

            if (!(empty($request->paginate))) {
                $workSchedules = $this->workScheduleRepository->searchWorkSchedule($filter)->paginate($request->paginate);
            } else {
                $workSchedules = $this->workScheduleRepository->searchWorkSchedule($filter)->get();
            }

            foreach ($workSchedules as $workSchedule) {
                $workSchedule->work_schedule_time = json_decode($workSchedule->work_schedule_time);
                $workSchedule->hospital_infrastructure = json_decode($workSchedule->hospital_infrastructure);
                $workSchedule->service_infor = json_decode($workSchedule->service_infor);
                $rating = Rating::where('id_work_schedule', $workSchedule->id)->first();
                $workSchedule->rating = $rating;
            }

            return $this->responseOK(200, $workSchedules, 'Xem tất cả lịch tư vấn và dịch vụ thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function userCancel(Request $request, $id_work_schedule)
    {
        try {
            $workSchedule = $this->workScheduleRepository->findById($id_work_schedule);
            if (empty($workSchedule)) {
                return $this->responseError(400, 'Không tìm thấy lịch tư vấn hay dịch vụ !');
            }

            $user = Auth::user();
            if ($workSchedule->id_user != $user->id) {
                return $this->responseError(403, 'Bạn không có quyền !');
            }

            $filter = (object) [
                'search' => '',
                'user_id' => $user->id,
                'work_schedule_id' => $id_work_schedule,
                'role' => 'user',
            ];
            $workSchedule = $this->workScheduleRepository->searchWorkSchedule($filter)->first();

            // gửi mail đến bác sĩ , bệnh viện , người dùng
            $workSchedule->work_schedule_time = json_decode($workSchedule->work_schedule_time);
            $startTime = $workSchedule->work_schedule_time->interval[0];
            $endTime = $workSchedule->work_schedule_time->interval[1];
            $day = $workSchedule->work_schedule_time->date;

            $contentHospital = "Lịch làm việc giữa bác sĩ $workSchedule->doctor_name và khách hàng $workSchedule->user_name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã bị bởi người dùng !";
            $contentUser = "Lịch làm việc giữa bạn và bác sĩ $workSchedule->doctor_name thuộc bệnh viện $workSchedule->hospital_name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã được hủy thành công !";

            if ($workSchedule->doctor_email) {
                $contentDoctor = "Lịch làm việc giữa bạn và $workSchedule->user_name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã bị bởi người dùng !";
                Queue::push(new SendMailNotify($workSchedule->doctor_email, $contentDoctor));
            }
            Queue::push(new SendMailNotify($workSchedule->hospital_email, $contentHospital));
            Queue::push(new SendMailNotify($workSchedule->user_email, $contentUser));

            $workSchedule->delete();
            Rating::where('id_work_schedule', $id_work_schedule)->delete(); // xóa lịch => xóa rating tương ứng

            return $this->responseOK(200, null, 'Hủy bỏ lịch tư vấn , dịch vụ thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function hospitalCancel(Request $request, $id_work_schedule)
    {
        try {
            $hospital = Auth::user();
            $filter = (object) [
                'search' => '',
                'hospital_id' => $hospital->id,
                'work_schedule_id' => $id_work_schedule,
                'role' => 'hospital',
            ];
            $workSchedule = $this->workScheduleRepository->searchWorkSchedule($filter)->first();
            if (empty($workSchedule)) {
                return $this->responseError(400, 'Không tìm thấy lịch tư vấn hay dịch vụ !');
            }

            // gửi mail đến bác sĩ , bệnh viện , người dùng
            $workSchedule->work_schedule_time = json_decode($workSchedule->work_schedule_time);
            $startTime = $workSchedule->work_schedule_time->interval[0];
            $endTime = $workSchedule->work_schedule_time->interval[1];
            $day = $workSchedule->work_schedule_time->date;

            $contentHospital = "Lịch làm việc giữa bác sĩ $workSchedule->doctor_name và khách hàng $workSchedule->user_name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã được hủy thành công !";
            $contentUser = "Lịch làm việc giữa bạn và bác sĩ $workSchedule->doctor_name thuộc bệnh viện $workSchedule->hospital_name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã bị hủy bởi bệnh viện !";

            if ($workSchedule->doctor_email) {
                $contentDoctor = "Lịch làm việc giữa bạn và $workSchedule->user_name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã bị bởi bệnh viện !";
                Queue::push(new SendMailNotify($workSchedule->doctor_email, $contentDoctor));
            }
            Queue::push(new SendMailNotify($workSchedule->hospital_email, $contentHospital));
            Queue::push(new SendMailNotify($workSchedule->user_email, $contentUser));

            $workSchedule->delete();
            Rating::where('id_work_schedule', $id_work_schedule)->delete(); // xóa lịch => xóa rating tương ứng
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function userCancelMany(Request $request)
    {
        try {
            $list_id = $request->list_id ?? [0];
            $user = Auth::user();
            $filter = [
                'list_id' => $list_id,
                'user_id' => $user->id,
                'search' => '',
                'role' => 'user',
            ];
            $workSchedules = $this->workScheduleRepository->searchWorkSchedule($filter)->get();

            if (count($workSchedules) > 0) {
                foreach ($workSchedules as $workSchedule) {
                    $workSchedule->work_schedule_time = json_decode($workSchedule->work_schedule_time);
                    $startTime = $workSchedule->work_schedule_time->interval[0];
                    $endTime = $workSchedule->work_schedule_time->interval[1];
                    $day = $workSchedule->work_schedule_time->date;

                    $contentHospital = "Lịch làm việc giữa bác sĩ $workSchedule->doctor_name và khách hàng $workSchedule->user_name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã bị bởi người dùng !";
                    $contentUser = "Lịch làm việc giữa bạn và bác sĩ $workSchedule->doctor_name thuộc bệnh viện $workSchedule->hospital_name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã được hủy thành công !";

                    if ($workSchedule->doctor_email) {
                        $contentDoctor = "Lịch làm việc giữa bạn và $workSchedule->user_name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã bị bởi người dùng !";
                        Queue::push(new SendMailNotify($workSchedule->doctor_email, $contentDoctor));
                    }
                    Queue::push(new SendMailNotify($workSchedule->hospital_email, $contentHospital));
                    Queue::push(new SendMailNotify($workSchedule->user_email, $contentUser));

                    $workSchedule->delete();
                    Rating::where('id_work_schedule', $workSchedule->work_schedule_id)->delete();
                }
            } else {
                return $this->responseError(200, 'Không tìm thấy lịch nào !');
            }

            return $this->responseOK(200, null, 'Hủy nhiều lịch thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function hospitalCancelMany(Request $request)
    {
        try {
            $list_id = $request->list_id ?? [0];
            $user = Auth::user();
            $filter = [
                'search' => '',
                'list_id' => $list_id,
                'hospital_id' => $user->id,
                'role' => 'hospital',
            ];
            $workSchedules = $this->workScheduleRepository->searchWorkSchedule($filter)->get();

            if (count($workSchedules) > 0) {
                foreach ($workSchedules as $workSchedule) {
                    $workSchedule->work_schedule_time = json_decode($workSchedule->work_schedule_time);
                    $startTime = $workSchedule->work_schedule_time->interval[0];
                    $endTime = $workSchedule->work_schedule_time->interval[1];
                    $day = $workSchedule->work_schedule_time->date;

                    $contentHospital = "Lịch làm việc giữa bác sĩ $workSchedule->doctor_name và khách hàng $workSchedule->user_name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã được hủy thành công !";
                    $contentUser = "Lịch làm việc giữa bạn và bác sĩ $workSchedule->doctor_name thuộc bệnh viện $workSchedule->hospital_name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã bị hủy bởi bệnh viện !";

                    if ($workSchedule->doctor_email) {
                        $contentDoctor = "Lịch làm việc giữa bạn và $workSchedule->user_name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã bị bởi bệnh viện !";
                        Queue::push(new SendMailNotify($workSchedule->doctor_email, $contentDoctor));
                    }
                    Queue::push(new SendMailNotify($workSchedule->hospital_email, $contentHospital));
                    Queue::push(new SendMailNotify($workSchedule->user_email, $contentUser));

                    $workSchedule->delete();
                    Rating::where('id_work_schedule', $workSchedule->work_schedule_id)->delete();
                }
            } else {
                return $this->responseError(200, 'Không tìm thấy lịch nào !');
            }

            return $this->responseOK(200, null, 'Hủy nhiều lịch thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }
}
