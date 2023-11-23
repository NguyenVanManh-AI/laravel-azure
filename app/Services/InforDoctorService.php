<?php

namespace App\Services;

use App\Enums\UserEnum;
use App\Http\Requests\RequestUpdateDoctor;
use App\Jobs\SendMailNotify;
use App\Jobs\SendVerifyEmail;
use App\Models\Department;
use App\Models\InforDoctor;
use App\Models\InforExtendDoctor;
use App\Models\InforHospital;
use App\Models\User;
use App\Models\WorkSchedule;
use App\Repositories\HospitalDepartmentRepository;
use App\Repositories\InforDoctorInterface;
use App\Repositories\InforDoctorRepository;
use App\Repositories\RatingRepository;
use App\Repositories\TimeWorkRepository;
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

            $department = Department::find($inforUser->id_department);
            $inforUser->department = $department;

            $inforExtendDoctor = InforExtendDoctor::where('id_doctor', $user->id)->first();
            $inforExtendDoctor->prominent = json_decode($inforExtendDoctor->prominent);
            $inforExtendDoctor->strengths = json_decode($inforExtendDoctor->strengths);
            $inforExtendDoctor->work_experience = json_decode($inforExtendDoctor->work_experience);
            $inforExtendDoctor->training_process = json_decode($inforExtendDoctor->training_process);
            $inforExtendDoctor->language = json_decode($inforExtendDoctor->language);
            $inforExtendDoctor->awards_recognition = json_decode($inforExtendDoctor->awards_recognition);
            $inforExtendDoctor->research_work = json_decode($inforExtendDoctor->research_work);
            $inforUser->infor_extend = $inforExtendDoctor;

            $doctor = array_merge($user->toArray(), $inforUser->toArray());

            // không được code như này , mà phải code như trên :
            // $user = UserRepository::findUserById(auth('user_api')->user()->id);
            // $inforUser = InforDoctorRepository::getInforDoctor(['id_doctor' => $user->id])->first();
            // $doctor = array_merge($user->toArray(), $inforUser->toArray());
            // $doctor->infor_extend = $inforExtendDoctor; // nếu muốn code như này thì phải chuyển $doctor về object

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

                    // department
                    $department = Department::find($inforUser->id_department);
                    $inforUser->department = $department;
                    // department

                    // infor hospital
                    $hospital = User::find($inforUser->id_hospital);
                    $inforHospital = InforHospital::where('id_hospital', $inforUser->id_hospital)->first();
                    $inforHospital->infrastructure = json_decode($inforHospital->infrastructure);
                    $inforHospital->location = json_decode($inforHospital->location);
                    $inforUser->infor_hospital = array_merge($hospital->toArray(), $inforHospital->toArray());
                    // infor hospital

                    // time work
                    $filter = (object) [
                        'id_hospital' => $inforUser->id_hospital,
                    ];
                    $timeWork = TimeWorkRepository::getTimeWork($filter)->first();
                    $timeWork->times = json_decode($timeWork->times);
                    $inforUser->time_work = $timeWork;
                    // time work

                    // infor extend doctor
                    $inforExtendDoctor = InforExtendDoctor::where('id_doctor', $inforUser->id_doctor)->first();

                    $inforExtendDoctor->prominent = json_decode($inforExtendDoctor->prominent);
                    $inforExtendDoctor->strengths = json_decode($inforExtendDoctor->strengths);
                    $inforExtendDoctor->work_experience = json_decode($inforExtendDoctor->work_experience);
                    $inforExtendDoctor->training_process = json_decode($inforExtendDoctor->training_process);
                    $inforExtendDoctor->language = json_decode($inforExtendDoctor->language);
                    $inforExtendDoctor->awards_recognition = json_decode($inforExtendDoctor->awards_recognition);
                    $inforExtendDoctor->research_work = json_decode($inforExtendDoctor->research_work);

                    $inforUser->infor_extend = $inforExtendDoctor;
                    // infor extend doctor

                    // rating
                    $bigRating = (object) [];
                    $workSchedules = WorkSchedule::where('id_doctor', $inforUser->id_doctor)->get();
                    $idWorkSchedules = $workSchedules->pluck('id')->toArray();

                    $cout_rating = 0;
                    $sum_rating = 0;
                    $bigRating->cout_rating = 0;
                    $bigRating->number_rating = 0;
                    $bigRating->cout_details = null;
                    $bigRating->ratings = null;
                    if (count($workSchedules) > 0) {
                        $filter = (object) [
                            'list_id_work_schedule' => $idWorkSchedules,
                        ];
                        $ratings = RatingRepository::getRating($filter)->paginate(6);
                        $bigRating->ratings = $ratings;

                        $cout_details = [];
                        for ($i = 1; $i <= 5; $i++) {
                            $filter->number_rating = $i;
                            $cout_details["{$i}_star"] = RatingRepository::getRating($filter)->count();
                        }
                        $bigRating->cout_details = $cout_details;

                        foreach ($cout_details as $key => $count) {
                            $rating = (int) $key;
                            $cout_rating += $count;
                            $sum_rating += $rating * $count;
                        }
                        $bigRating->cout_rating = $cout_rating;
                        $bigRating->number_rating = ($cout_rating != 0) ? round($sum_rating / $cout_rating, 1) : 0;
                    }
                    $inforUser->rating = $bigRating;
                    // rating

                    $doctor = array_merge($user->toArray(), $inforUser->toArray());

                    return $this->responseOK(200, $doctor, 'Xem thông tin tài khoản thành công !');
                }
            }

            return $this->responseError(400, 'Không tìm thấy tài khoản !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function moreRating(Request $request, $id_doctor)
    {
        try {
            $doctor = InforDoctor::where('id_doctor', $id_doctor)->first();
            if ($doctor) {
                $moreRating = (object) [];

                // ratings
                $workSchedules = WorkSchedule::where('id_doctor', $doctor->id_doctor)->get();
                $idWorkSchedules = $workSchedules->pluck('id')->toArray();

                $cout_rating = 0;
                $sum_rating = 0;
                $moreRating->cout_rating = 0;
                $moreRating->number_rating = 0;
                $moreRating->cout_details = null;
                $moreRating->ratings = null;
                if (count($workSchedules) > 0) {
                    $filter = (object) [
                        'list_id_work_schedule' => $idWorkSchedules,
                    ];

                    $page = $request->page;
                    $ratings = RatingRepository::getRating($filter)->paginate(6);

                    $moreRating->ratings = $ratings;

                    $cout_details = [];
                    for ($i = 1; $i <= 5; $i++) {
                        $filter->number_rating = $i;
                        $cout_details["{$i}_star"] = RatingRepository::getRating($filter)->count();
                    }
                    $moreRating->cout_details = $cout_details;

                    foreach ($cout_details as $key => $count) {
                        $rating = (int) $key;
                        $cout_rating += $count;
                        $sum_rating += $rating * $count;
                    }
                    $moreRating->cout_rating = $cout_rating;
                    $moreRating->number_rating = ($cout_rating != 0) ? round($sum_rating / $cout_rating, 1) : 0;
                }

                return $this->responseOK(200, $moreRating, 'Xem đánh giá chi tiết thành công !');
            } else {
                return $this->responseError(400, 'Không tìm thấy bác sĩ trong bệnh viện !');
            }
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function bookDoctor(Request $request, $id_hospital, $id_department)
    {
        try {
            // hospital
            $user = UserRepository::findUserById($id_hospital);
            if (empty($user) || $user->role != 'hospital') {
                return $this->responseError(400, 'Không tìm thấy bệnh viện !');
            }

            // hospitalDepartment
            $hospitalDepartment = HospitalDepartmentRepository::searchHospitalDepartment([
                'id_department' => $id_department,
                'id_hospital' => $id_hospital,
            ])->first();

            if (empty($hospitalDepartment)) {
                return $this->responseError(400, 'Không tìm thấy khoa trong bệnh viện !');
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
                $request['avatar'] = $user->avatar;
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
                $url = UserEnum::VERIFY_MAIL_USER . $token;
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
