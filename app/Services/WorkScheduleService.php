<?php

namespace App\Services;

use App\Http\Requests\RequestCreateWorkScheduleAdvise;
use App\Http\Requests\RequestCreateWorkScheduleService;
use App\Jobs\SendMailNotify;
use App\Repositories\HospitalServiceRepository;
use App\Repositories\InforDoctorRepository;
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

            $time = $request->time;
            $filter = [
                'time' => $time,
                'id_doctor' => $request->id_doctor,
                'id_service' => 'advise',
            ];
            $findWorkSchedule = WorkScheduleRepository::getWorkSchedule($filter)->count();
            if ($findWorkSchedule > 0) {
                return $this->responseError(400, 'Lịch này đã được đặt !');
            }

            $startTime = $time['interval'][0];
            $endTime = $time['interval'][1];
            $date = $time['date'];

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
            ];
            $hospitalServices = HospitalServiceRepository::searchAll($filter)->first();
            if (empty($hospitalServices)) {
                return $this->responseError(404, 'Không tìm thấy dịch vụ trong bệnh viện !');
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

            $listIdDoctor = [];
            foreach ($allDoctor as $index => $doctor) {
                $listIdDoctor[] = $doctor->id_doctor;
            }

            // tại [dịch vụ] và [khoảng thời gian] đó => lấy ra danh sách id bác sĩ bận
            $time = $request->time;
            $filter = [
                'time' => $time,
                'id_service' => $request->id_hospital_service,
            ];
            $workScheduleServices = WorkScheduleRepository::getWorkSchedule($filter)->get();
            $busyIdDoctor = [];
            foreach ($workScheduleServices as $index => $workSchedule) {
                $busyIdDoctor[] = $workSchedule->id_doctor;
            }

            // danh sách id của những bác sĩ rảnh rỗi
            $freeTimeIdDoctor = array_diff($listIdDoctor, $busyIdDoctor);

            if (count($freeTimeIdDoctor) == 0) {
                return $this->responseError(400, 'Lịch dịch vụ này đã được đặt ! Tất cả bác sĩ của chuyên khoa đều bận ! Vui lòng chọn khung giờ khác !');
            }

            // random từ danh sách phần nào cũng đảm bảo tính công bằng , nhưng chính xác hơn là phải tính toán
            // xem nhưng bác sĩ làm nhiều ca sẽ được loại ra

            // từ danh sách tất cả bác sĩ , loại bỏ đi các bác sĩ bận vào khung giờ và dịch vụ đó
            // còn lại chỉ định một bác sĩ bất kì từ danh sách còn lại .
            // nếu như mảng còn lại có count < 0 => tất cả các bác sĩ của khoa đều bận vào khung giờ đó
            // => không thể đặt lịch

            $hospital = UserRepository::findUserById($hospitalServices->id_hospital);
            $specifiedDoctor = $freeTimeIdDoctor[array_rand($freeTimeIdDoctor)];
            $user = Auth::user();
            $time = $request->time;
            $startTime = $time['interval'][0];
            $endTime = $time['interval'][1];
            $date = $time['date'];

            $doctor = UserRepository::doctorOfHospital(['id_doctor' => $specifiedDoctor])->first();
            $content = "Bạn có lịch dịch vụ $hospitalServices->name với bác sĩ $doctor->name_doctor thuộc chuyên khoa " .
            " $doctor->name_department của bệnh viện $hospital->name vào khoản thời gian từ lúc $startTime cho đến " .
            " $endTime của ngày $date tại địa chỉ $hospital->address.  SĐT Liên hệ bệnh viện : $hospital->phone .";

            $data = [
                'id_user' => $user->id,
                'id_doctor' => $specifiedDoctor,
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

    public function hospitalWorkSchedule(Request $request)
    {
        try {
            try {
                $user = UserRepository::findUserById(auth('user_api')->user()->id);

                $doctors = InforDoctorRepository::getInforDoctor(['id_hospital' => $user->id])->get();
                $idDoctorHospitals = [];

                if ($request->doctors_id) {
                    $idDoctorHospitals[] = $request->doctors_id;
                } else {
                    foreach ($doctors as $doctor) {
                        $idDoctorHospitals[] = $doctor->id_doctor;
                    }
                }

                $search = $request->search;

                $orderBy = 'work_schedules.id';
                $orderDirection = 'ASC';

                if ($request->sortlatest == 'true') {
                    $orderBy = 'work_schedules.id';
                    $orderDirection = 'DESC';
                }

                if ($request->sortname == 'true') {
                    $orderBy = 'users_user.name';
                    $orderDirection = ($request->sortlatest == 'true') ? 'DESC' : 'ASC';
                }

                if ($request->sortprice == 'true') {
                    $orderBy = 'work_schedules.price';
                    $orderDirection = 'DESC';
                }

                if ($request->sorttime == 'true') {
                    $orderBy = 'time->date'; // mặc định là sắp xếp thời gian gần nhất lên đầu
                    $orderDirection = 'ASC';
                }

                $filter = (object) [
                    'search' => $search,
                    'department_name' => $request->department_name ?? '',
                    'doctors_id' => $idDoctorHospitals,
                    'is_service' => $request->is_service ?? '',
                    'start_date' => $request->start_date ?? '',
                    'end_date' => $request->end_date ?? '',
                    'orderBy' => $orderBy,
                    'orderDirection' => $orderDirection,
                    'role' => 'hospital',
                ];

                if (!(empty($request->paginate))) {
                    $workSchedules = $this->workScheduleRepository->searchWorkSchedule($filter)->paginate($request->paginate);
                } else {
                    $workSchedules = $this->workScheduleRepository->searchWorkSchedule($filter)->get();
                }

                foreach ($workSchedules as $workSchedule) {
                    $workSchedule->time = json_decode($workSchedule->time);
                    $workSchedule->infrastructure = json_decode($workSchedule->infrastructure);
                    $workSchedule->infor = json_decode($workSchedule->infor);
                    $workSchedule->hospital_service_infor = json_decode($workSchedule->hospital_service_infor);
                }

                return $this->responseOK(200, $workSchedules, 'Xem tất cả lịch tư vấn và dịch vụ thành công !');
            } catch (Throwable $e) {
                return $this->responseError(400, $e->getMessage());
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function doctorWorkSchedule(Request $request)
    {
        try {
            $user = UserRepository::findUserById(auth('user_api')->user()->id);
            $idDoctorHospitals = [];
            $idDoctorHospitals[] = $user->id;

            $search = $request->search;

            $orderBy = 'work_schedules.id';
            $orderDirection = 'ASC';

            if ($request->sortlatest == 'true') {
                $orderBy = 'work_schedules.id';
                $orderDirection = 'DESC';
            }

            if ($request->sortname == 'true') {
                $orderBy = 'users_user.name';
                $orderDirection = ($request->sortlatest == 'true') ? 'DESC' : 'ASC';
            }

            if ($request->sortprice == 'true') {
                $orderBy = 'work_schedules.price';
                $orderDirection = 'DESC';
            }

            if ($request->sorttime == 'true') {
                $orderBy = 'time->date'; // mặc định là sắp xếp thời gian gần nhất lên đầu
                $orderDirection = 'ASC';
            }

            $filter = (object) [
                'search' => $search,
                'doctors_id' => $idDoctorHospitals,
                'is_service' => $request->is_service ?? '',
                'start_date' => $request->start_date ?? '',
                'end_date' => $request->end_date ?? '',
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
                'role' => 'doctor',
            ];

            if (!(empty($request->paginate))) {
                $workSchedules = $this->workScheduleRepository->searchWorkSchedule($filter)->paginate($request->paginate);
            } else {
                $workSchedules = $this->workScheduleRepository->searchWorkSchedule($filter)->get();
            }

            foreach ($workSchedules as $workSchedule) {
                $workSchedule->time = json_decode($workSchedule->time);
                $workSchedule->infrastructure = json_decode($workSchedule->infrastructure);
                $workSchedule->infor = json_decode($workSchedule->infor);
                $workSchedule->hospital_service_infor = json_decode($workSchedule->hospital_service_infor);
            }

            return $this->responseOK(200, $workSchedules, 'Xem tất cả lịch tư vấn và dịch vụ thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function userBook(Request $request)
    {
        try {
            $user = UserRepository::findUserById(auth('user_api')->user()->id);

            $search = $request->search;

            $orderBy = 'work_schedules.id';
            $orderDirection = 'ASC';

            if ($request->sortlatest == 'true') {
                $orderBy = 'work_schedules.id';
                $orderDirection = 'DESC';
            }

            if ($request->sortname == 'true') {
                $orderBy = 'users_user.name';
                $orderDirection = ($request->sortlatest == 'true') ? 'DESC' : 'ASC';
            }

            if ($request->sortprice == 'true') {
                $orderBy = 'work_schedules.price';
                $orderDirection = 'DESC';
            }

            if ($request->sorttime == 'true') {
                $orderBy = 'time->date'; // mặc định là sắp xếp thời gian gần nhất lên đầu
                $orderDirection = 'ASC';
            }

            $filter = (object) [
                'search' => $search,
                'user_id' => $user->id,
                'is_service' => $request->is_service ?? '',
                'start_date' => $request->start_date ?? '',
                'end_date' => $request->end_date ?? '',
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
                'role' => 'user',
            ];

            if (!(empty($request->paginate))) {
                $workSchedules = $this->workScheduleRepository->searchWorkSchedule($filter)->paginate($request->paginate);
            } else {
                $workSchedules = $this->workScheduleRepository->searchWorkSchedule($filter)->get();
            }

            foreach ($workSchedules as $workSchedule) {
                $workSchedule->time = json_decode($workSchedule->time);
                $workSchedule->infrastructure = json_decode($workSchedule->infrastructure);
                $workSchedule->infor = json_decode($workSchedule->infor);
                $workSchedule->hospital_service_infor = json_decode($workSchedule->hospital_service_infor);
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
                return $this->responseError(404, 'Không tìm thấy lịch tư vấn hay dịch vụ !');
            }

            $user = Auth::user();
            if ($workSchedule->id_user != $user->id) {
                return $this->responseError(403, 'Bạn không có quyền !');
            }

            $doctor = UserRepository::doctorOfHospital(['id_doctor' => $workSchedule->id_doctor])->first();
            $hospital = UserRepository::findUserById($doctor->id_hospital);

            // gửi mail đến bác sĩ , bệnh viện , người dùng
            $workSchedule->time = json_decode($workSchedule->time);
            $startTime = $workSchedule->time->interval[0];
            $endTime = $workSchedule->time->interval[1];
            $day = $workSchedule->time->date;

            $contentDoctor = "Lịch làm việc giữa bạn và $user->name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã bị bởi người dùng !";
            $contentHospital = "Lịch làm việc giữa bác sĩ $doctor->name_doctor và khách hàng $user->name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã bị bởi người dùng !";
            $contentUser = "Lịch làm việc giữa bạn và $doctor->name_doctor trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã được hủy thành công !";

            Queue::push(new SendMailNotify($doctor->email, $contentDoctor));
            Queue::push(new SendMailNotify($hospital->email, $contentHospital));
            Queue::push(new SendMailNotify($user->email, $contentUser));

            $workSchedule->delete();

            return $this->responseOK(200, null, 'Hủy bỏ lịch tư vấn , dịch vụ thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function hospitalCancel(Request $request, $id_work_schedule)
    {
        try {
            $workSchedule = $this->workScheduleRepository->findById($id_work_schedule);
            if (empty($workSchedule)) {
                return $this->responseError(404, 'Không tìm thấy lịch tư vấn hay dịch vụ !');
            }

            $hospital = Auth::user();
            $doctors = InforDoctorRepository::getInforDoctor(['id_hospital' => $hospital->id])->get();
            $idDoctorHospitals = [];
            foreach ($doctors as $doctor) {
                $idDoctorHospitals[] = $doctor->id_doctor;
            }

            if (!in_array($workSchedule->id_doctor, $idDoctorHospitals)) {
                return $this->responseError(403, 'Bạn không có quyền !');
            }

            $doctor = UserRepository::doctorOfHospital(['id_doctor' => $workSchedule->id_doctor])->first();
            $user = UserRepository::findUserById($workSchedule->id_user);

            // gửi mail đến bác sĩ , bệnh viện , người dùng
            $workSchedule->time = json_decode($workSchedule->time);
            $startTime = $workSchedule->time->interval[0];
            $endTime = $workSchedule->time->interval[1];
            $day = $workSchedule->time->date;

            $contentDoctor = "Lịch làm việc giữa bạn và $user->name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã bị hủy bởi bệnh viện !";
            $contentHospital = "Lịch làm việc giữa bác sĩ $doctor->name_doctor và khách hàng $user->name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã được hủy !";
            $contentUser = "Lịch làm việc giữa bạn và $doctor->name_doctor trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã bị hủy bởi bệnh viện !";

            Queue::push(new SendMailNotify($doctor->email, $contentDoctor));
            Queue::push(new SendMailNotify($hospital->email, $contentHospital));
            Queue::push(new SendMailNotify($user->email, $contentUser));

            $workSchedule->delete();

            return $this->responseOK(200, null, 'Hủy bỏ lịch tư vấn , dịch vụ thành công !');
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
                'list_id' => $list_id,
                'hospital_id' => $user->id,
            ];
            $doctors = InforDoctorRepository::getInforDoctor(['id_hospital' => $user->id])->get();
            $idDoctorHospitals = [];
            foreach ($doctors as $doctor) {
                $idDoctorHospitals[] = $doctor->id_doctor;
            }
            $workSchedules = $this->workScheduleRepository->searchWorkSchedule($filter)->get();
            if (count($workSchedules) > 0) {
                foreach ($workSchedules as $workSchedule) {
                    $hospital = Auth::user();
                    if (!in_array($workSchedule->id_doctor, $idDoctorHospitals)) {
                        continue;
                    }

                    $doctor = UserRepository::doctorOfHospital(['id_doctor' => $workSchedule->id_doctor])->first();
                    $user = UserRepository::findUserById($workSchedule->id_user);

                    // gửi mail đến bác sĩ , bệnh viện , người dùng
                    $workSchedule->time = json_decode($workSchedule->time);
                    $startTime = $workSchedule->time->interval[0];
                    $endTime = $workSchedule->time->interval[1];
                    $day = $workSchedule->time->date;

                    $contentDoctor = "Lịch làm việc giữa bạn và $user->name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã bị hủy bởi bệnh viện !";
                    $contentHospital = "Lịch làm việc giữa bác sĩ $doctor->name_doctor và khách hàng $user->name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã được hủy !";
                    $contentUser = "Lịch làm việc giữa bạn và $doctor->name_doctor trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã bị hủy bởi bệnh viện !";

                    Queue::push(new SendMailNotify($doctor->email, $contentDoctor));
                    Queue::push(new SendMailNotify($hospital->email, $contentHospital));
                    Queue::push(new SendMailNotify($user->email, $contentUser));

                    $workSchedule = $this->workScheduleRepository->findById($workSchedule->work_schedule_id);
                    $workSchedule->delete();
                }
            } else {
                return $this->responseError(200, 'Không tìm thấy lịch nào !');
            }

            return $this->responseOK(200, null, 'Hủy nhiều lịch thành công !');
        } catch (Throwable $e) {
            return $this->responseError(404, $e->getMessage());
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
            ];
            $workSchedules = $this->workScheduleRepository->searchWorkSchedule($filter)->get();
            if (count($workSchedules) > 0) {
                foreach ($workSchedules as $workSchedule) {
                    $doctor = UserRepository::doctorOfHospital(['id_doctor' => $workSchedule->id_doctor])->first();
                    $hospital = UserRepository::findUserById($doctor->id_hospital);

                    // gửi mail đến bác sĩ , bệnh viện , người dùng
                    $workSchedule->time = json_decode($workSchedule->time);
                    $startTime = $workSchedule->time->interval[0];
                    $endTime = $workSchedule->time->interval[1];
                    $day = $workSchedule->time->date;

                    $contentDoctor = "Lịch làm việc giữa bạn và $user->name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã bị bởi người dùng !";
                    $contentHospital = "Lịch làm việc giữa bác sĩ $doctor->name_doctor và khách hàng $user->name trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã bị bởi người dùng !";
                    $contentUser = "Lịch làm việc giữa bạn và $doctor->name_doctor trong khoảng thời gian từ $startTime đến $endTime vào ngày $day đã được hủy thành công !";

                    Queue::push(new SendMailNotify($doctor->email, $contentDoctor));
                    Queue::push(new SendMailNotify($hospital->email, $contentHospital));
                    Queue::push(new SendMailNotify($user->email, $contentUser));

                    $workSchedule = $this->workScheduleRepository->findById($workSchedule->work_schedule_id);
                    $workSchedule->delete();
                }
            } else {
                return $this->responseError(200, 'Không tìm thấy lịch nào !');
            }

            return $this->responseOK(200, null, 'Hủy nhiều lịch thành công !');
        } catch (Throwable $e) {
            return $this->responseError(404, $e->getMessage());
        }
    }
}
