<?php

namespace App\Services;

use App\Enums\UserEnum;
use App\Http\Requests\RequestChangePassword;
use App\Http\Requests\RequestChangeRole;
use App\Http\Requests\RequestCreateNewAdmin;
use App\Http\Requests\RequestCreatePassword;
use App\Http\Requests\RequestSendForgot;
use App\Http\Requests\RequestUpdateAdmin;
use App\Jobs\SendForgotPasswordEmail;
use App\Jobs\SendMailNotify;
use App\Jobs\SendPasswordNewAdmin;
use App\Jobs\SendVerifyEmail;
use App\Models\Admin;
use App\Models\User;
use App\Repositories\AdminInterface;
use App\Repositories\PasswordResetRepository;
use App\Repositories\UserRepository;
use Brian2694\Toastr\Facades\Toastr;
use Faker\Factory;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class AdminService
{
    protected AdminInterface $adminRepository;

    public function __construct(
        AdminInterface $adminRepository,
    ) {
        $this->adminRepository = $adminRepository;
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('admin_api')->factory()->getTTL() * 60,
        ]);
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

    public function login(Request $request)
    {
        try {
            $credentials = request(['email', 'password']);
            $user = $this->adminRepository->findAdminByEmail($request->email);
            if ($user->email_verified_at == null) {
                $message = 'Email này chưa được xác nhận , hãy kiểm tra và xác nhận nó trước khi đăng nhập !';

                return $this->responseError(400, $message);
            }

            if (!$token = auth()->guard('admin_api')->attempt($credentials)) {
                return $this->responseError(400, 'Email hoặc mật khẩu không đúng !');
            }

            $user->access_token = $token;
            $user->token_type = 'bearer';
            $user->expires_in = auth()->guard('admin_api')->factory()->getTTL() * 60;

            return $this->responseOK(200, $user, 'Đăng nhập thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function changePassword(RequestChangePassword $request)
    {
        try {
            $admin = $this->adminRepository->findAdminById(auth('admin_api')->user()->id);
            if (!(Hash::check($request->get('current_password'), $admin->password))) {
                return $this->responseError(400, 'Mật khẩu của bạn không chính xác !');
            }

            $data = ['password' => Hash::make($request->get('new_password'))];
            $this->adminRepository->updateAdmin($admin->id, $data);

            return $this->responseOK(200, null, 'Thay đổi mật khẩu thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function saveAvatar(Request $request)
    {
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/image/avatars/admins', $filename);

            return 'storage/image/avatars/admins/' . $filename;
        }
    }

    public function updateProfile(RequestUpdateAdmin $request)
    {
        try {
            $id_admin = auth('admin_api')->user()->id;
            $admin = $this->adminRepository->findAdminById($id_admin);
            $oldEmail = $admin->email;
            if ($request->hasFile('avatar')) {
                if ($admin->avatar) {
                    File::delete($admin->avatar);
                }
                $avatar = $this->saveAvatar($request);
                $data = array_merge($request->all(), ['avatar' => $avatar]);
                $admin = $this->adminRepository->updateAdmin($admin->id, $data);
            } else {
                $admin = $this->adminRepository->updateAdmin($admin->id, $request->all());
            }
            $message = 'Cập nhật thông tin tài khoản admin thành công !';
            // sendmail verify
            if ($oldEmail != $request->email) {
                $token = Str::random(32);
                $url = UserEnum::DOMAIN_PATH . 'admin/verify-email/' . $token;
                Queue::push(new SendVerifyEmail($admin->email, $url));
                $content = 'Tài khoản của bạn đã thay đổi email thành ' . $admin->email . '. Nếu bạn không làm điều này, hãy liên hệ với quản trị viên của hệ thống để được hỗ trợ . ';
                Queue::push(new SendMailNotify($oldEmail, $content));

                $data = [
                    'token_verify_email' => $token,
                    'email_verified_at' => null,
                ];
                $admin = $this->adminRepository->updateAdmin($admin->id, $data);
                $message = 'Cập nhật thông tin thành công . Một email xác nhận đã được gửi hãy kiểm tra mail và xác nhận nó !';
            }
            // sendmail verify
            return $this->responseOK(201, $admin, $message);
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    // verify email
    public function verifyEmail($token)
    {
        $admin = $this->adminRepository->findAdminByTokenVerifyEmail($token);
        if ($admin) {
            $data = [
                'email_verified_at' => now(),
                'token_verify_email' => null,
            ];
            $admin = $this->adminRepository->updateAdmin($admin->id, $data);
            $status = true;
            Toastr::success('Email của bạn đã được xác nhận !');
        } else {
            $status = false;
            Toastr::warning('Token đã hết hạn !');
        }

        return view('admin.status_verify_email', ['status' => $status]);
    }

    public function allAdmin(Request $request)
    {
        try {
            $search = $request->search;
            $orderBy = 'id';
            $orderDirection = 'ASC';

            if ($request->sortlatest == 'true') {
                $orderBy = 'id';
                $orderDirection = 'DESC';
            }

            if ($request->sortname == 'true') {
                $orderBy = 'name';
                $orderDirection = ($request->sortlatest == 'true') ? 'DESC' : 'ASC';
            }

            $filter = (object) [
                'search' => $search,
                'role' => $request->role ?? '',
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
            ];

            if (!(empty($request->paginate))) {
                $allAdmin = $this->adminRepository->searchAdmin($filter)->paginate($request->paginate);

                return $this->responseOK(200, $allAdmin, 'Xem tất cả quản trị thành công !');
            } else {
                $allAdmin = $this->adminRepository->searchAdmin($filter)->get();

                return $this->responseOK(200, $allAdmin, 'Xem tất cả quản trị thành công !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function allUser(Request $request)
    {
        try {
            $search = $request->search;
            $orderBy = 'id';
            $orderDirection = 'ASC';

            if ($request->sortlatest == 'true') {
                $orderBy = 'id';
                $orderDirection = 'DESC';
            }

            if ($request->sortname == 'true') {
                $orderBy = 'name';
                $orderDirection = ($request->sortlatest == 'true') ? 'DESC' : 'ASC';
            }

            $filter = (object) [
                'search' => $search,
                'role' => $request->role ?? '',
                'orderBy' => $orderBy,
                'is_accept' => $request->is_accept ?? 'both',
                'orderDirection' => $orderDirection,
            ];

            if (!(empty($request->paginate))) {
                $allUser = UserRepository::searchUser($filter)->paginate($request->paginate);

                return $this->responseOK(200, $allUser, 'Xem tất cả người dùng thành công !');
            } else {
                $allUser = UserRepository::searchUser($filter)->get();

                return $this->responseOK(200, $allUser, 'Xem tất cả người dùng thành công !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function forgotSend(RequestSendForgot $request)
    {
        try {
            $email = $request->email;
            $token = Str::random(32);
            $isUser = 0;
            $user = PasswordResetRepository::findPasswordReset($email, $isUser);
            if ($user) {
                $user->update(['token' => $token]);
            } else {
                PasswordResetRepository::createToken($email, $isUser, $token);
            }
            $url = UserEnum::DOMAIN_PATH . 'admin/forgot-form?token=' . $token;
            Log::info("Add jobs to Queue , Email: $email with URL: $url");
            Queue::push(new SendForgotPasswordEmail($email, $url));

            return $this->responseOK(201, null, 'Gửi mail đặt lại mật khẩu thành công , hãy kiểm tra mail !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function forgotUpdate(RequestCreatePassword $request)
    {
        try {
            $new_password = Hash::make($request->new_password);
            $passwordReset = PasswordResetRepository::findByToken($request->token);
            if ($passwordReset) { // user, doctor, hospital
                if ($passwordReset->is_user == 1) {
                    $user = UserRepository::findUserByEmail($passwordReset->email);
                    if ($user) {
                        $user->update(['password' => $new_password]);
                        $passwordReset->delete();

                        Toastr::success('Đặt lại mật khẩu thành công !');

                        return redirect()->route('form_reset_password');
                    }
                    Toastr::warning('Không tìm thấy tài khoản !');

                    return redirect()->route('form_reset_password');
                } else { // admin, superamdin, manager
                    $admin = $this->adminRepository->findAdminByEmail($passwordReset->email);
                    if ($admin) {
                        $admin->update(['password' => $new_password]);
                        $passwordReset->delete();

                        Toastr::success('Đặt lại mật khẩu thành công !');

                        return redirect()->route('admin_form_reset_password');
                    }
                    Toastr::warning('Không tìm thấy tài khoản !');

                    return redirect()->route('admin_form_reset_password');
                }
            } else {
                Toastr::warning('Token đã hết hạn !');

                return redirect()->route('admin_form_reset_password');
            }
        } catch (Throwable $e) {
        }
    }

    public function addAdmin(RequestCreateNewAdmin $request)
    {
        try {
            // Cách 1
            // $faker = Factory::create();
            // $fakeImageUrl = $faker->imageUrl(200, 200, 'admins');
            // $imageContent = file_get_contents($fakeImageUrl);
            // $imageName = 'avatar_admin_' . time() . '.jpg';
            // Storage::put('public/image/avatars/admins/' . $imageName, $imageContent);
            // $avatar = 'storage/image/avatars/admins/' . $imageName;

            // Cách 2
            // $avatar = null;
            // $pathFolder = 'storage/image/avatars/admins';
            // if (!File::exists($pathFolder)) {
            //     File::makeDirectory($pathFolder, 0755, true);
            // }
            // $client = new Client;
            // while (true) {
            //     try {
            //         $response = $client->get('https://picsum.photos/200/200');
            //         $imageContent = $response->getBody()->getContents();
            //         $pathFolder = 'storage/image/avatars/admins/';
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
                'name' => $request->name,
                'password' => Hash::make($new_password),
                'role' => 'admin',
                'avatar' => null,
                'token_verify_email' => null,
                'email_verified_at' => now(),
            ];

            $newAdmin = $this->adminRepository->createAdmin($data);
            Queue::push(new SendPasswordNewAdmin($request->email, $new_password));

            return $this->responseOK(201, $newAdmin, 'Thêm quản trị viên thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function deleteAdmin($id)
    {
        try {
            $adminLogin = auth('admin_api')->user();
            $admin = $this->adminRepository->findAdminById($id);
            if ($admin->role == 'manager') {
                return $this->responseError(400, 'Không được phép xóa tài khoản giám đốc !');
            }
            if ($admin->role == 'superadmin' && $adminLogin->role == 'superadmin') {
                return $this->responseError(403, 'Bạn không có quyền , chỉ có giám đốc mới có quyền xóa superadmin !');
            }
            if ($admin->avatar) {
                File::delete($admin->avatar);
            }
            $admin->delete();

            return $this->responseOK(200, null, 'Xóa tài khoản thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function editRole(RequestChangeRole $request, $id)
    {
        try {
            $adminLogin = auth('admin_api')->user();
            $admin = $this->adminRepository->findAdminById($id);
            if ($admin->role == 'manager') {
                return $this->responseError(400, 'Không được phép chỉnh sửa quyền của giám đốc !');
            }
            if ($request->role == $admin->role) {
                return $this->responseOK(200, $admin, 'Thay đổi role thành công !');
            }
            if ($adminLogin->role == 'superadmin') {
                if ($request->role == 'admin') {
                    $message = 'Bạn không có quyền , chỉ có giám đốc mới có quyền thay đổi role từ superadmin xuống admin !';

                    return $this->responseError(403, $message);
                }
            }
            $data = ['role' => $request->role];
            $admin = $this->adminRepository->updateAdmin($admin->id, $data);

            return $this->responseOK(200, $admin, 'Thay đổi role cho quản trị viên thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function changeAccept(Request $request, $id)
    {
        try {
            $user = UserRepository::findUserById($id);
            $data = ['is_accept' => $request->is_accept];
            $user = UserRepository::updateUser($user->id, $data);

            return $this->responseOK(200, null, 'Thay đổi trạng thái của người dùng thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }
}
