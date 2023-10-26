<?php

namespace App\Services;

use App\Enums\UserEnum;
use App\Http\Requests\RequestChangeConfirmDoctor;
use App\Http\Requests\RequestCreateInforHospital;
use App\Http\Requests\RequestCreateNewDoctor;
use App\Http\Requests\RequestUpdateHospital;
use App\Jobs\SendMailNotify;
use App\Jobs\SendVerifyEmail;
use App\Repositories\DepartmentRepository;
use App\Repositories\HospitalDepartmentRepository;
use App\Repositories\InforDoctorRepository;
use App\Repositories\InforHospitalInterface;
use App\Repositories\InforHospitalRepository;
use App\Repositories\ProvinceRepository;
use App\Repositories\TimeWorkRepository;
use App\Repositories\UserRepository;
use Database\Factories\FakeImageFactory;
use Faker\Factory;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class InforHospitalService
{
    protected InforHospitalInterface $inforHospitalRepository;

    public function __construct(
        InforHospitalInterface $inforHospitalRepository
    ) {
        $this->inforHospitalRepository = $inforHospitalRepository;
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
            $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_hospital_' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/image/avatars/hospitals/', $filename);

            return 'storage/image/avatars/hospitals/' . $filename;
        }
    }

    public function register(RequestCreateInforHospital $request)
    {
        try {
            $filter = [
                'email' => $request->email ?? '',
                'role' => 'hospital',
            ];
            $userEmail = UserRepository::findUser($filter)->first();
            if ($userEmail) {
                return $this->responseError(400, 'Tài khoản đã tồn tại !');
            } else {
                $avatar = $this->saveAvatar($request);
                $data = array_merge(
                    $request->all(),
                    ['password' => Hash::make($request->password), 'is_accept' => 0, 'role' => 'hospital', 'avatar' => $avatar]
                );
                $user = UserRepository::createUser($data);
                $request->merge([
                    'infrastructure' => json_encode($request->infrastructure),
                    'location' => json_encode($request->location),
                ]);

                $data = array_merge(
                    $request->all(),
                    ['id_hospital' => $user->id]
                );

                $inforUser = InforHospitalRepository::createHospital($data);

                // verify email
                $token = Str::random(32);
                $url = UserEnum::DOMAIN_PATH . 'verify-email/' . $token;
                Queue::push(new SendVerifyEmail($user->email, $url));
                $data = ['token_verify_email' => $token];
                $user = UserRepository::updateUser($user->id, $data);
                // verify email

                $inforUser->infrastructure = json_decode($inforUser->infrastructure);
                $inforUser->location = json_decode($inforUser->location);

                // addTimeWork
                $timeDefault = [
                    'enable' => true,
                    'morning' => [
                        'enable' => true,
                        'time' => ['7:30', '11:30'],
                    ],
                    'afternoon' => [
                        'enable' => true,
                        'time' => ['13:30', '17:30'],
                    ],
                    'night' => [
                        'enable' => true,
                        'time' => ['18:00', '20:00'],
                    ],
                ];

                $dataTimeWork = [
                    'id_hospital' => $user->id,
                    'enable' => true,
                    'note' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'times' => json_encode([
                        'monday' => $timeDefault,
                        'tuesday' => $timeDefault,
                        'wednesday' => $timeDefault,
                        'thursday' => $timeDefault,
                        'friday' => $timeDefault,
                        'saturday' => $timeDefault,
                        'sunday' => $timeDefault,
                    ]),
                ];
                $timeWork = TimeWorkRepository::createTimeWork($dataTimeWork);
                $timeWork->times = json_decode($timeWork->times);
                // addTimeWork

                $hospital = array_merge($user->toArray(), $inforUser->toArray(), $timeWork->toArray());

                return $this->responseOK(201, $hospital, 'Đăng kí tài khoản thành công . Hãy kiểm tra mail và xác nhận nó !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function profile()
    {
        try {
            $user = UserRepository::findUserById(auth('user_api')->user()->id);
            $inforUser = InforHospitalRepository::getInforHospital(['id_hospital' => $user->id])->first();
            $inforUser->infrastructure = json_decode($inforUser->infrastructure);
            $inforUser->location = json_decode($inforUser->location);

            $hospital = array_merge($user->toArray(), $inforUser->toArray());

            return $this->responseOK(200, $hospital, 'Xem thông tin tài khoản thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function viewProfile(Request $request, $id)
    {
        try {
            $user = UserRepository::findUserById($id);
            if (!empty($user) && $user->role == 'hospital' && $user->is_accept == 1) {
                $inforUser = InforHospitalRepository::getInforHospital(['id_hospital' => $user->id])->first();

                // search number
                $search_number = $inforUser->search_number + 1;
                $inforUser = InforHospitalRepository::updateHospital($inforUser, ['search_number' => $search_number]);
                // search number

                $inforUser->infrastructure = json_decode($inforUser->infrastructure);
                $inforUser->location = json_decode($inforUser->location);

                // time work
                $filter = (object) [
                    'id_hospital' => $user->id,
                ];
                $timeWork = TimeWorkRepository::getTimeWork($filter)->first();
                $timeWork->times = json_decode($timeWork->times);
                $inforUser->time_work = $timeWork;

                // department
                $listNames = [];
                $filter = (object) [
                    'id_hospital' => $id,
                ];
                $hospitalDepartments = HospitalDepartmentRepository::searchHospitalDepartment($filter)->get();
                foreach ($hospitalDepartments as $hospitalDepartment) {
                    $listNames[] = $hospitalDepartment->name;
                }
                $inforUser->departments = $listNames;

                $hospital = array_merge($user->toArray(), $inforUser->toArray());

                return $this->responseOK(200, $hospital, 'Xem thông tin tài khoản thành công !');
            } else {
                return $this->responseError(400, 'Không tìm thấy tài khoản !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function updateProfile(RequestUpdateHospital $request)
    {
        try {
            $user = UserRepository::findUserById(auth('user_api')->user()->id);
            $oldEmail = $user->email;

            $request->merge([
                'infrastructure' => json_encode($request->infrastructure),
                'location' => json_encode($request->location),
            ]);

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

            $inforHospital = InforHospitalRepository::getInforHospital(['id_hospital' => $user->id])->first();
            $inforHospital = InforHospitalRepository::updateInforHospital($inforHospital->id, $request->all());
            $message = 'Hospital successfully updated';

            // sendmail verify
            if ($oldEmail != $request->email) {
                $token = Str::random(32);
                $url = UserEnum::DOMAIN_PATH . 'verify-email/' . $token;
                Queue::push(new SendVerifyEmail($user->email, $url));
                $new_email = $user->email;
                $content = 'Email tài khoản của bạn đã được thay đổi thành ' . $new_email . ' Nếu bạn không phải là người thực hiện thay đổi này , hãy liên hệ với quản trị viên của hệ thống để được hỗ trợ ! ';
                Queue::push(new SendMailNotify($oldEmail, $content));
                $data = [
                    'token_verify_email' => $token,
                    'email_verified_at' => null,
                ];
                $user = UserRepository::updateUser($user->id, $data);
                $message = 'Cập nhật thông tin bệnh viện thành công . Một mail xác nhận đã được gửi đến cho bạn , hãy kiểm tra và xác nhận nó !';
            }
            // sendmail verify

            $inforHospital->infrastructure = json_decode($inforHospital->infrastructure);
            $inforHospital->location = json_decode($inforHospital->location);

            $hospital = array_merge($user->toArray(), $inforHospital->toArray());

            return $this->responseOK(200, $hospital, $message);
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function addDoctor(RequestCreateNewDoctor $request)
    {
        DB::beginTransaction();
        try {
            $department = DepartmentRepository::findById($request->id_department);
            if (empty($department)) {
                return $this->responseError(404, 'Không tìm thấy khoa !');
            }
            $hospital = UserRepository::findUserById(auth('user_api')->user()->id);

            // Cách 1 dùng Factory
            // $fakeImageFactory = FakeImageFactory::new();
            // $avatar = $fakeImageFactory->createAvatarDoctor();
            // while (!$avatar) {
            //     $avatar = $fakeImageFactory->createAvatarDoctor();
            // }
            // $avatar = 'storage/image/avatars/doctors/' . $avatar;

            // Cách 2 dùng "guzzlehttp/guzzle": "^7.8"
            // $avatar = null;
            // $pathFolder = 'storage/image/avatars/doctors';
            // if (!File::exists($pathFolder)) {
            //     File::makeDirectory($pathFolder, 0755, true);
            // }
            // $client = new Client;
            // while (true) {
            //     try {
            //         $response = $client->get('https://picsum.photos/200/200');
            //         $imageContent = $response->getBody()->getContents();
            //         $pathFolder = 'storage/image/avatars/doctors/';
            //         $nameImage = uniqid() . '.jpg';
            //         $avatar = $pathFolder . $nameImage;
            //         file_put_contents($avatar, $imageContent);
            //         if (file_exists($avatar)) {
            //             break;
            //         }
            //     } catch (Throwable $e) {
            //     }
            // }

            $new_password = Str::random(10);
            $data = [
                'email' => $request->email,
                'password' => Hash::make($new_password),
                'name' => $request->name,
                'avatar' => null,
                'is_accept' => true,
                'role' => 'doctor',
                'token_verify_email' => null,
                'email_verified_at' => now(),
            ];
            $doctor = UserRepository::createUser($data);

            $data = [
                'id_doctor' => $doctor->id,
                'id_department' => $request->id_department,
                'id_hospital' => $hospital->id,
                'is_confirm' => true,
                'province_code' => $request->province_code,
            ];
            $inforDoctor = InforDoctorRepository::createDoctor($data);

            $content = 'Dưới đây là thông tin tài khoản của bạn , hãy sử dụng nó để đăng nhập vào hệ thống , sau đó hãy tiến hành đổi mật khẩu để đảm bảo tính bảo mật cho tài khoản . <br> email: ' . $doctor->email . ' <br> password: ' . $new_password;
            Queue::push(new SendMailNotify($doctor->email, $content));

            DB::commit();

            $hospital = array_merge($doctor->toArray(), $inforDoctor->toArray());

            return $this->responseOK(201, $hospital, 'Thêm tài khoản bác sĩ thành công !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError(400, $e->getMessage());
        }
    }

    public function allDoctor(Request $request)
    {
        try {
            $search = $request->search;
            $orderBy = 'users.id';
            $orderDirection = 'ASC';

            if ($request->sortlatest == 'true') {
                $orderBy = 'users.id';
                $orderDirection = 'DESC';
            }

            if ($request->sortname == 'true') {
                $orderBy = 'users.name';
                $orderDirection = ($request->sortlatest == 'true') ? 'DESC' : 'ASC';
            }

            $user = Auth::user();

            $filter = (object) [
                'search' => $search,
                'role' => 'doctor',
                'orderBy' => $orderBy,
                'is_accept' => $request->is_accept ?? 'both',
                'is_confirm' => $request->is_confirm ?? 'both',
                'name_department' => $request->name_department ?? '',
                'id_hospital' => $user->id,
                'orderDirection' => $orderDirection,
            ];

            if (!(empty($request->paginate))) {
                $allDoctor = UserRepository::doctorOfHospital($filter)->paginate($request->paginate);

                return $this->responseOK(200, $allDoctor, 'Xem tất cả bác sĩ thành công !');
            } else {
                $allDoctor = UserRepository::doctorOfHospital($filter)->get();

                return $this->responseOK(200, $allDoctor, 'Xem tất cả bác sĩ thành công !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function allDoctorCare(Request $request)
    {
        try {
            $search = $request->search;
            $orderBy = 'users.id';
            $orderDirection = 'ASC';

            if ($request->sortlatest == 'true') {
                $orderBy = 'users.id';
                $orderDirection = 'DESC';
            }

            if ($request->sortname == 'true') {
                $orderBy = 'users.name';
                $orderDirection = ($request->sortlatest == 'true') ? 'DESC' : 'ASC';
            }

            $filter = (object) [
                'search' => $search,
                'role' => 'doctor',
                'orderBy' => $orderBy,
                'is_accept' => 1,
                'is_confirm' => 1,
                'name_department' => $request->name_department ?? '',
                'orderDirection' => $orderDirection,
            ];

            if (!(empty($request->paginate))) {
                $allDoctor = UserRepository::doctorOfHospital($filter)->paginate($request->paginate);
            } else {
                $allDoctor = UserRepository::doctorOfHospital($filter)->get();
            }

            return $this->responseOK(200, $allDoctor, 'Xem tất cả bác sĩ thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function allDoctorHome(Request $request, $id)
    {
        try {
            $user = UserRepository::findUserById($id);
            if (empty($user) || $user->role != 'hospital') {
                return $this->responseError(404, 'Không tìm thấy bệnh viện !');
            }

            $search = $request->search;
            $orderBy = 'users.id';
            $orderDirection = 'ASC';

            if ($request->sortlatest == 'true') {
                $orderBy = 'users.id';
                $orderDirection = 'DESC';
            }

            if ($request->sortname == 'true') {
                $orderBy = 'users.name';
                $orderDirection = ($request->sortlatest == 'true') ? 'DESC' : 'ASC';
            }

            $filter = (object) [
                'search' => $search,
                'role' => 'doctor',
                'orderBy' => $orderBy,
                'is_accept' => 1,
                'is_confirm' => 1,
                'name_department' => $request->name_department ?? '',
                'id_hospital' => $id,
                'orderDirection' => $orderDirection,
            ];

            if (!(empty($request->paginate))) {
                $allDoctor = UserRepository::doctorOfHospital($filter)->paginate($request->paginate);

                return $this->responseOK(200, $allDoctor, 'Xem tất cả bác sĩ của bệnh viện thành công !');
            } else {
                $allDoctor = UserRepository::doctorOfHospital($filter)->get();

                return $this->responseOK(200, $allDoctor, 'Xem tất cả bác sĩ của bệnh viện thành công !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function allHospital(Request $request)
    {
        try {
            $search = $request->search;
            $orderBy = 'users.id';
            $orderDirection = 'ASC';

            if ($request->sortlatest == 'true') {
                $orderBy = 'users.id';
                $orderDirection = 'DESC';
            }

            if ($request->sortname == 'true') {
                $orderBy = 'users.name';
                $orderDirection = ($request->sortlatest == 'true') ? 'DESC' : 'ASC';
            }

            // sắp xếp theo bài viết nổi bật
            if ($request->sort_search_number == 'true') {
                $orderBy = 'infor_hospitals.search_number';
                $orderDirection = 'DESC';
            }

            $filter = (object) [
                'search' => $search,
                'is_accept' => 1,
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
            ];

            if (!(empty($request->paginate))) {
                $hospitals = InforHospitalRepository::searchHospital($filter)->paginate($request->paginate);
                foreach ($hospitals as $hospital) {
                    $hospital->infrastructure = json_decode($hospital->infrastructure);
                    $hospital->location = json_decode($hospital->location);
                }

                return $this->responseOK(200, $hospitals, 'Xem tất cả bệnh viện thành công !');
            } else {
                $hospitals = InforHospitalRepository::searchHospital($filter)->get();
                foreach ($hospitals as $hospital) {
                    $hospital->infrastructure = json_decode($hospital->infrastructure);
                    $hospital->location = json_decode($hospital->location);
                }

                return $this->responseOK(200, $hospitals, 'Xem tất cả bệnh viện thành công !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function bookHospital(Request $request, $province_code)
    {
        try {
            $province = ProvinceRepository::getProvince(['province_code' => $province_code])->get();
            if (count($province) <= 0) {
                return $this->responseError(404, 'Không tìm thấy mã tỉnh thành !');
            }
            // không dùng empty vì get rỗng cũng không phải empty
            $filter = (object) [
                'province_code' => $province_code,
                'is_accept' => 1,
            ];
            $hospitals = InforHospitalRepository::searchHospital($filter)->get();
            if (count($hospitals) <= 0) {
                return $this->responseError(200, 'Không tìm thấy bệnh viện trong tỉnh thành này !');
            }

            return $this->responseOK(200, $hospitals, 'Xem tất cả bệnh viện thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function changeConfirm(RequestChangeConfirmDoctor $request, $id)
    {
        try {
            $user = Auth::user();
            $filter = [
                'id_doctor' => $id,
                'id_hospital' => $user->id,
            ];
            $doctor = InforDoctorRepository::getInforDoctor($filter)->first();
            if (empty($doctor)) {
                return $this->responseError(404, 'Không tìm bác sĩ trong bệnh viện !');
            }

            $data = ['is_confirm' => $request->is_confirm];
            $doctor = InforDoctorRepository::updateResult($doctor, $data);

            return $this->responseOK(200, $doctor, 'Thay đổi trạng thái của bác sĩ thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }
}
