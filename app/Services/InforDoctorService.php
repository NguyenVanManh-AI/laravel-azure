<?php

namespace App\Services;

use App\Enums\UserEnum;
use App\Http\Requests\RequestUpdateDoctor;
use App\Jobs\SendMailNotify;
use App\Jobs\SendVerifyEmail;
use App\Models\InforHospital;
use App\Models\User;
use App\Repositories\HospitalDepartmentRepository;
use App\Repositories\InforDoctorInterface;
use App\Repositories\InforDoctorRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Throwable;

class InforDoctorService
{
    protected InforDoctorInterface $inforDoctorRepository;

    public function __construct(
        InforDoctorInterface $inforDoctorRepository
    ) {
        $this->inforDoctorRepository = $inforDoctorRepository;
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

    public function saveAvatar(Request $request)
    {
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_doctor_' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/image/avatars/doctors/', $filename);

            return 'storage/image/avatars/doctors/' . $filename;
        }
    }

    public function profile()
    {
        try {
            $user = UserRepository::findUserById(auth('user_api')->user()->id);
            $inforUser = InforDoctorRepository::getInforDoctor(['id_doctor' => $user->id])->first();

            $doctor = array_merge($user->toArray(), $inforUser->toArray());

            return $this->responseOK(200, $doctor, 'Xem thông tin cá nhân thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function viewProfile(Request $request, $id)
    {
        try {
            $user = UserRepository::findUserById($id);
            if (!empty($user) && $user->role == 'doctor' && $user->is_accept == 1) {
                $inforUser = InforDoctorRepository::getInforDoctor(['id_doctor' => $user->id])->first();
                if ($inforUser->is_confirm == 1) {
                    // search number
                    $search_number = $inforUser->search_number + 1;
                    $inforUser = InforDoctorRepository::updateResult($inforUser, ['search_number' => $search_number]);
                    // search number

                    $hospital = array_merge($user->toArray(), $inforUser->toArray());

                    return $this->responseOK(200, $hospital, 'Xem thông tin tài khoản thành công !');
                }
            }

            return $this->responseError(400, 'Không tìm thấy tài khoản !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function bookDoctor(Request $request, $id_hospital, $id_department)
    {
        try {
            // hospital
            $user = UserRepository::findUserById($id_hospital);
            if (empty($user) || $user->role != 'hospital') {
                return $this->responseError(404, 'Không tìm thấy bệnh viện !');
            }

            // hospitalDepartment
            $hospitalDepartment = HospitalDepartmentRepository::searchHospitalDepartment([
                'id_department' => $id_department,
                'id_hospital' => $id_hospital,
            ])->first();

            if (empty($hospitalDepartment)) {
                return $this->responseError(404, 'Không tìm thấy khoa trong bệnh viện !');
            }

            $filter = (object) [
                'role' => 'doctor',
                'is_accept' => 1,
                'is_confirm' => 1,
                'id_department' => $id_department,
                'id_hospital' => $id_hospital,
            ];
            $allDoctor = UserRepository::doctorOfHospital($filter)->get();

            return $this->responseOK(200, $allDoctor, 'Xem tất cả bác sĩ của khoa trong bệnh viện thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function updateProfile(RequestUpdateDoctor $request)
    {
        try {
            $user = UserRepository::findUserById(auth('user_api')->user()->id);
            $oldEmail = $user->email;
            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    File::delete($user->avatar);
                }
                $avatar = $this->saveAvatar($request);
                $data = array_merge($request->all(), ['avatar' => $avatar]);
                $user = UserRepository::updateUser($user->id, $data);
            } else {
                $user = UserRepository::updateUser($user->id, $request->all());
            }

            $inforDoctor = InforDoctorRepository::getInforDoctor(['id_doctor' => $user->id])->first();
            $inforDoctor = InforDoctorRepository::updateInforDoctor($inforDoctor->id, $request->all());

            // $oldHospital = InforHospital::find($inforDoctor->id_hospital);
            // $emailOldHospital = User::find($oldHospital->id_hospital)->email;
            // $newHospital = InforHospital::find($request->id_hospital);
            // $emailNewHospital = User::find($newHospital->id_hospital)->email;
            $message = 'Cập nhật thông tin bác sĩ thành công !';

            // sendmail verify
            if ($oldEmail != $request->email) {
                $token = Str::random(32);
                $url = UserEnum::DOMAIN_PATH . 'verify-email/' . $token;
                Queue::push(new SendVerifyEmail($user->email, $url));
                $new_email = $user->email;
                $content = 'Email tài khoản của bạn đã chuyển thành ' . $new_email . ' Nếu bạn không phải là người thực hiện , hãy liên hệ với quản trị viên của hệ thống để được hỗ trợ . ';
                Queue::push(new SendMailNotify($oldEmail, $content));
                $data = [
                    'token_verify_email' => $token,
                    'email_verified_at' => null,
                ];
                $user = UserRepository::updateUser($user->id, $data);
                $message = 'Cập nhật thông tin thành công . Một mail xác nhận đã được gửi đến cho bạn , hãy kiểm tra và xác nhận nó !';
            }
            // sendmail verify

            $doctor = array_merge($user->toArray(), $inforDoctor->toArray());

            return $this->responseOK(200, $doctor, $message);
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }
}
