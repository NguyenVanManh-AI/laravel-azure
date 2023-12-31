<?php

namespace App\Services;

use App\Http\Requests\RequestConfirmWorkSchedule;
use App\Http\Requests\RequestCreateWorkScheduleAdvise;
use App\Http\Requests\RequestCreateWorkScheduleService;
use App\Http\Requests\RequestUpdateInforPatient;
use App\Jobs\SendMailDefault;
use App\Jobs\SendMailNotify;
use App\Models\InforDoctor;
use App\Models\Rating;
use App\Models\User;
use App\Models\WorkSchedule;
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
            $user = null;
            if ($request->hasHeader('Authorization')) {
                $user = Auth::guard('user_api')->user();
            }

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

            $infors = (object) [
                'messsge' => false,
                'name_patient' => $request->name_patient,
                'email_patient' => $request->email_patient,
                'gender_patient' => $request->gender_patient,
                'phone_patient' => $request->phone_patient,
                'address_patient' => $request->address_patient,
                'health_condition' => $request->health_condition,
                'date_of_birth_patient' => $request->date_of_birth_patient,
                'name_doctor' => $doctor->name_doctor,
                'email_doctor' => $doctor->email,
                'phone_doctor' => $doctor->phone,
                'name_department' => $doctor->name_department,
                'name_hospital' => $hospital->name,
                'phone_hospital' => $hospital->phone,
                'address_hospital' => $hospital->address,
                'price' => $doctor->price,
                'time' => $time,
            ];

            $content = view('emails.book_advise', compact(['infors', 'user']))->render();

            $data = [
                'id_user' => $user->id ?? null,
                'id_doctor' => $request->id_doctor,
                'id_service' => null,
                'price' => $doctor->price,
                'time' => json_encode($time),
                'content' => $content,
                'name_patient' => $request->name_patient,
                'email_patient' => $request->email_patient,
                'gender_patient' => $request->gender_patient,
                'phone_patient' => $request->phone_patient,
                'address_patient' => $request->address_patient,
                'health_condition' => $request->health_condition,
                'date_of_birth_patient' => $request->date_of_birth_patient,
            ];
            $workSchedule = WorkScheduleRepository::createWorkSchedule($data);
            $workSchedule->time = json_decode($workSchedule->time);

            $infors->messsge = 'Đặt lịch tư vấn thành công !';
            $content = view('emails.book_advise', compact(['infors', 'user']))->render();
            if ($user) {
                Queue::push(new SendMailNotify($user->email, $content));
            }
            Queue::push(new SendMailNotify($request->email_patient, $content));

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

            $user = null;
            if ($request->hasHeader('Authorization')) {
                $user = Auth::guard('user_api')->user();
            }

            $time = (object) $request->time;
            $startTime = $time->interval[0];
            $endTime = $time->interval[1];
            $date = $time->date;

            $infors = (object) [
                'messsge' => false,
                'name_patient' => $request->name_patient,
                'email_patient' => $request->email_patient,
                'gender_patient' => $request->gender_patient,
                'phone_patient' => $request->phone_patient,
                'address_patient' => $request->address_patient,
                'health_condition' => $request->health_condition,
                'date_of_birth_patient' => $request->date_of_birth_patient,
                'name_service' => $hospitalServices->name,
                'price' => $hospitalServices->price,
                'name_department' => $hospitalServices->name_department,
                'name_hospital' => $hospital->name,
                'phone_hospital' => $hospital->phone,
                'address_hospital' => $hospital->address,
                'time' => $time,
            ];
            $content = view('emails.book_service', compact(['infors', 'user']))->render();

            $data = [
                'id_user' => $user->id ?? null,
                'id_doctor' => null,
                'id_service' => $request->id_hospital_service,
                'price' => $hospitalServices->price_hospital_service,
                'time' => json_encode($time),
                'content' => $content,
                'name_patient' => $request->name_patient,
                'email_patient' => $request->email_patient,
                'gender_patient' => $request->gender_patient,
                'phone_patient' => $request->phone_patient,
                'address_patient' => $request->address_patient,
                'health_condition' => $request->health_condition,
                'date_of_birth_patient' => $request->date_of_birth_patient,
            ];
            $workSchedule = WorkScheduleRepository::createWorkSchedule($data);
            $workSchedule->time = json_decode($workSchedule->time);

            $infors->messsge = 'Đặt lịch dịch vụ thành công !';
            $content = view('emails.book_service', compact(['infors', 'user']))->render();
            if ($user) {
                Queue::push(new SendMailNotify($user->email, $content));
            }
            Queue::push(new SendMailNotify($request->email_patient, $content));

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

            // if ($workSchedule->doctor_id != null) {
            //     return $this->responseError(400, 'Dịch vụ này đã có bác sĩ thực hiện !');
            // } else {
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
            // if (empty($free_time_doctors)) {
            //     return $this->responseOK(200, null, 'Tất cả các bác sĩ đều bận trong khung giờ này !');
            // }

            $free_time_doctors[] = $workSchedule->doctor_id; // LẤY THÊM BÁC SĨ HIỆN TẠI RA NỮA
            $listDoctors = User::whereIn('id', $free_time_doctors)->get();
            foreach ($listDoctors as $doctor) {
                $doctor->id_doctor = $doctor->id;
            }
            $start = $time->interval[0];
            $end = $time->interval[1];

            return $this->responseOK(200, $listDoctors, "Lấy danh sách bác sĩ rảnh trong khung giờ $start đến $end ngày $time->date thành công !");
            // }
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

            // listSpecifyDoctor LẤY THÊM BÁC SĨ HIỆN TẠI => UPDATE LẠI DOCTOR Hiện tại thì không làm gì cả
            if ($workSchedule->doctor_id == $id_doctor) {
                return $this->responseOK(200, $workSchedule, 'Chỉ định bác sĩ thành công !');
            }

            // if ($workSchedule->doctor_id != null) {
            //     return $this->responseError(400, 'Dịch vụ này đã có bác sĩ thực hiện !');
            // }

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
            $filter2 = (object) [
                'id_doctors' => [$id_doctor],
                'time' => $time,
            ];
            $busy = $this->workScheduleRepository->getWorkSchedule($filter2)->count();
            if ($busy > 0) {
                return $this->responseError(400, 'Bác sĩ bận tại khung giờ này !');
            }

            $workSchedule->update([
                'id_doctor' => $id_doctor,
            ]);
            $workSchedule = $this->workScheduleRepository->searchWorkSchedule($filter)->first();

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

                case 'new':
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
                'is_specify' => $request->is_specify ?? '',
                'start_date' => $request->start_date ?? '',
                'end_date' => $request->end_date ?? '',
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
                'status' => $request->status ?? '',
                'role' => 'hospital',
                'is_confirm' => $request->is_confirm ?? 'both',
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

                case 'new':
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
                'is_confirm' => $request->is_confirm ?? 'both',
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

                case 'new':
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
                'is_confirm' => $request->is_confirm ?? 'both',
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

            if ($workSchedule->is_confirm == 1) {
                return $this->responseError(400, 'Lịch đã được xác nhận , bạn không được phép hủy !');
            }

            $filter = (object) [
                'search' => '',
                'user_id' => $user->id,
                'work_schedule_id' => $id_work_schedule,
                'role' => 'user',
            ];
            $workSchedule = $this->workScheduleRepository->searchWorkSchedule($filter)->first();

            // gửi mail đến bác sĩ , bệnh viện , người dùng
            $content = $workSchedule->work_schedule_content;
            $thongbao = 'Lịch có thông tin chi tiết như sau đã được hủy thành công !';
            if ($workSchedule->email_patient) {
                Queue::push(new SendMailDefault($workSchedule->email_patient, $thongbao, $content));
            }
            if ($workSchedule->user_email) {
                Queue::push(new SendMailDefault($workSchedule->user_email, $thongbao, $content));
            }
            $thongbao = 'Lịch có thông tin chi tiết như sau đã được hủy bởi người dùng !';
            if ($workSchedule->doctor_email) {
                Queue::push(new SendMailDefault($workSchedule->doctor_email, $thongbao, $content));
            }
            if ($workSchedule->hospital_email) {
                Queue::push(new SendMailDefault($workSchedule->hospital_email, $thongbao, $content));
            }

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
            $content = $workSchedule->work_schedule_content;
            $thongbao = 'Lịch có thông tin chi tiết như sau đã được hủy thành công !';
            if ($workSchedule->hospital_email) {
                Queue::push(new SendMailDefault($workSchedule->hospital_email, $thongbao, $content));
            }
            $thongbao = 'Lịch có thông tin chi tiết như sau đã được hủy bởi bệnh viện !';
            if ($workSchedule->email_patient) {
                Queue::push(new SendMailDefault($workSchedule->email_patient, $thongbao, $content));
            }
            if ($workSchedule->user_email) {
                Queue::push(new SendMailDefault($workSchedule->user_email, $thongbao, $content));
            }
            if ($workSchedule->doctor_email) {
                Queue::push(new SendMailDefault($workSchedule->doctor_email, $thongbao, $content));
            }

            $workSchedule->delete();
            Rating::where('id_work_schedule', $id_work_schedule)->delete(); // xóa lịch => xóa rating tương ứng

            return $this->responseOK(200, null, 'Hủy bỏ lịch tư vấn , dịch vụ thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function changeConfirm(RequestConfirmWorkSchedule $request, $id_work_schedule)
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

            // + Lý do : $message là một từ khóa đặc biệt => không nên đặt biến khi gửi mail
            // đặt là $thongbao hoặc từ nào đó chứ không nên đặt những từ có thể là từ khóa ví dụ như : $message là một từ khóa
            $thongbao = 'Lịch có thông tin chi tiết như sau đã thay đổi trạng thái sang chưa được xác nhận !';
            $content = $workSchedule->work_schedule_content;
            if ($request->is_confirm == '1') {
                $thongbao = 'Lịch có thông tin chi tiết như sau đã được xác nhận !';
            }
            if ($workSchedule->email_patient) {
                Queue::push(new SendMailDefault($workSchedule->email_patient, $thongbao, $content));
            }
            if ($workSchedule->user_email) {
                Queue::push(new SendMailDefault($workSchedule->user_email, $thongbao, $content));
            }
            if ($workSchedule->doctor_email) {
                Queue::push(new SendMailDefault($workSchedule->doctor_email, $thongbao, $content));
            }

            $workSchedule = WorkSchedule::find($id_work_schedule);
            $workSchedule->update([
                'is_confirm' => $request->is_confirm,
            ]);
            // gửi mail đến bác sĩ , bệnh viện , người dùng

            return $this->responseOK(200, null, 'Thay đổi trạng thái lịch tư vấn dịch vụ thành công !');
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
                'is_confirm' => 0,
            ];
            $workSchedules = $this->workScheduleRepository->searchWorkSchedule($filter)->get();

            if (count($workSchedules) > 0) {
                foreach ($workSchedules as $workSchedule) {
                    // gửi mail đến bác sĩ , bệnh viện , người dùng
                    $content = $workSchedule->work_schedule_content;
                    $thongbao = 'Lịch có thông tin chi tiết như sau đã được hủy thành công !';
                    if ($workSchedule->email_patient) {
                        Queue::push(new SendMailDefault($workSchedule->email_patient, $thongbao, $content));
                    }
                    if ($workSchedule->user_email) {
                        Queue::push(new SendMailDefault($workSchedule->user_email, $thongbao, $content));
                    }
                    $thongbao = 'Lịch có thông tin chi tiết như sau đã được hủy bởi người dùng !';
                    if ($workSchedule->doctor_email) {
                        Queue::push(new SendMailDefault($workSchedule->doctor_email, $thongbao, $content));
                    }
                    if ($workSchedule->hospital_email) {
                        Queue::push(new SendMailDefault($workSchedule->hospital_email, $thongbao, $content));
                    }

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
                    // gửi mail đến bác sĩ , bệnh viện , người dùng
                    $content = $workSchedule->work_schedule_content;
                    $thongbao = 'Lịch có thông tin chi tiết như sau đã được hủy thành công !';
                    if ($workSchedule->hospital_email) {
                        Queue::push(new SendMailDefault($workSchedule->hospital_email, $thongbao, $content));
                    }
                    $thongbao = 'Lịch có thông tin chi tiết như sau đã được hủy bởi bệnh viện !';
                    if ($workSchedule->email_patient) {
                        Queue::push(new SendMailDefault($workSchedule->email_patient, $thongbao, $content));
                    }
                    if ($workSchedule->user_email) {
                        Queue::push(new SendMailDefault($workSchedule->user_email, $thongbao, $content));
                    }
                    if ($workSchedule->doctor_email) {
                        Queue::push(new SendMailDefault($workSchedule->doctor_email, $thongbao, $content));
                    }

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

    public function updateInforPatient(RequestUpdateInforPatient $request, $id_work_schedule)
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

            $workSchedule = $workSchedule->update($request->all());
            $workSchedule = $this->workScheduleRepository->searchWorkSchedule($filter)->first();

            if ($workSchedule->service_id) { // DỊCH VỤ
                $filter = (object) [
                    'id_hospital_services' => $workSchedule->service_id,
                    'is_delete' => 0,
                ];
                $hospitalServices = HospitalServiceRepository::searchAll($filter)->first();

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
                $hospital = UserRepository::findUserById($hospitalServices->id_hospital);

                $user = null;
                if ($workSchedule->user_id) {
                    $user = User::find($workSchedule->user_id);
                }

                $time = $workSchedule->work_schedule_time;
                $time = json_decode($time);
                $startTime = $time->interval[0];
                $endTime = $time->interval[1];
                $date = $time->date;

                $infors = (object) [
                    'messsge' => false,
                    'name_patient' => $request->name_patient,
                    'email_patient' => $request->email_patient,
                    'gender_patient' => $request->gender_patient,
                    'phone_patient' => $request->phone_patient,
                    'address_patient' => $request->address_patient,
                    'health_condition' => $request->health_condition,
                    'date_of_birth_patient' => $request->date_of_birth_patient,
                    'name_service' => $hospitalServices->name,
                    'price' => $hospitalServices->price,
                    'name_department' => $hospitalServices->name_department,
                    'name_hospital' => $hospital->name,
                    'phone_hospital' => $hospital->phone,
                    'address_hospital' => $hospital->address,
                    'time' => $time,
                ];
                $content = view('emails.book_service', compact(['infors', 'user']))->render();
                $workSchedule = $workSchedule->update(['content' => $content]);
                $infors->messsge = 'Lịch dịch vụ của bạn đã được cập nhật lại như sau !';
                $content = view('emails.book_service', compact(['infors', 'user']))->render();
            } else { // TƯ VẤN
                $user = null;
                if ($workSchedule->user_id) {
                    $user = User::find($workSchedule->user_id);
                }

                $doctor = UserRepository::doctorOfHospital(['id_doctor' => $workSchedule->doctor_id])->first();
                $hospital = UserRepository::findUserById($doctor->id_hospital);

                $time = $workSchedule->work_schedule_time;
                $id_doctor = $request->id_doctor;
                $filter = (object) [
                    'id_doctors' => [$id_doctor],
                    'time' => $time,
                ];

                $time = json_decode($time);
                $startTime = $time->interval[0];
                $endTime = $time->interval[1];
                $date = $time->date;

                $infors = (object) [
                    'messsge' => false,
                    'name_patient' => $request->name_patient,
                    'email_patient' => $request->email_patient,
                    'gender_patient' => $request->gender_patient,
                    'phone_patient' => $request->phone_patient,
                    'address_patient' => $request->address_patient,
                    'health_condition' => $request->health_condition,
                    'date_of_birth_patient' => $request->date_of_birth_patient,
                    'name_doctor' => $doctor->name_doctor,
                    'email_doctor' => $doctor->email,
                    'phone_doctor' => $doctor->phone,
                    'name_department' => $doctor->name_department,
                    'name_hospital' => $hospital->name,
                    'phone_hospital' => $hospital->phone,
                    'address_hospital' => $hospital->address,
                    'price' => $doctor->price,
                    'time' => $time,
                ];
                $content = view('emails.book_advise', compact(['infors', 'user']))->render();
                $workSchedule = $workSchedule->update(['content' => $content]);
                $infors->messsge = 'Lịch tư vấn của bạn đã được cập nhật lại như sau !';
                $content = view('emails.book_advise', compact(['infors', 'user']))->render();
            }

            $filter = (object) [
                'search' => '',
                'hospital_id' => $hospital->id,
                'work_schedule_id' => $id_work_schedule,
                'role' => 'hospital',
            ];
            $workSchedule = $this->workScheduleRepository->searchWorkSchedule($filter)->first();
            $workSchedule->time = json_decode($workSchedule->time);

            if ($user) {
                Queue::push(new SendMailNotify($user->email, $content));
            }
            Queue::push(new SendMailNotify($request->email_patient, $content));

            return $this->responseOK(200, $workSchedule, 'Cập nhật thông tin bệnh nhân thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }
}
