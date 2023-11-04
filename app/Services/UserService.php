<?php

namespace App\Services;

use App\Enums\UserEnum;
use App\Http\Requests\RequestChangePassword;
use App\Http\Requests\RequestCreatePassword;
use App\Http\Requests\RequestSendForgot;
use App\Jobs\SendForgotPasswordEmail;
use App\Models\User;
use App\Repositories\InforDoctorRepository;
use App\Repositories\InforHospitalRepository;
use App\Repositories\InforUserRepository;
use App\Repositories\PasswordResetRepository;
use App\Repositories\UserInterface;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Throwable;

class UserService
{
    protected UserInterface $userRepository;

    public function __construct(
        UserInterface $userRepository,
    ) {
        $this->userRepository = $userRepository;
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('user_api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('user_api')->factory()->getTTL() * 60,
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
            $user = $this->userRepository->findUserByEmail($request->email);
            if (empty($user)) {
                return $this->responseError(400, 'Email không tồn tại !');
            } else {
                $is_accept = $user->is_accept;
                if ($is_accept != 1) {
                    return $this->responseError(400, 'Tài khoản của bạn đã bị khóa hoặc chưa được phê duyệt !');
                }
                if ($user->email_verified_at == null) {
                    return $this->responseError(400, 'Email này chưa được xác nhận , hãy kiểm tra và xác nhận nó trước khi đăng nhập !');
                }
            }

            $credentials = request(['email', 'password']);
            if (!$token = auth()->guard('user_api')->attempt($credentials)) {
                return $this->responseError(400, 'Email hoặc mật khẩu không chính xác !');
            }

            $user->have_password = true;
            if (!$user->password) {
                $user->have_password = false;
            } // login by gg chưa có password

            $inforUser = InforUserRepository::getInforUser(['id_user' => $user->id])->first();
            if ($user->role == 'hospital') {
                $inforUser = InforHospitalRepository::getInforHospital(['id_hospital' => $user->id])->first();
                $inforUser->infrastructure = json_decode($inforUser->infrastructure);
                $inforUser->location = json_decode($inforUser->location);
            }
            if ($user->role == 'doctor') {
                $inforUser = InforDoctorRepository::getInforDoctor(['id_doctor' => $user->id])->first();
                if ($inforUser->is_confirm != 1) {
                    return $this->responseError(400, 'Tài khoản của bạn chưa được phê duyệt !');
                }
            }

            $user->access_token = $token;
            $user->token_type = 'bearer';
            $user->expires_in = auth()->guard('user_api')->factory()->getTTL() * 60;

            $arrUser = array_merge($user->toArray(), $inforUser->toArray());

            return $this->responseOK(200, $arrUser, 'Đăng nhập thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function changePassword(RequestChangePassword $request)
    {
        try {
            $user = UserRepository::findUserById(auth('user_api')->user()->id);
            if (!(Hash::check($request->get('current_password'), $user->password))) {
                return $this->responseError(400, 'Mật khẩu không chính xác !');
            }
            $data = ['password' => Hash::make($request->get('new_password'))];
            $user = UserRepository::updateUser($user->id, $data);

            return $this->responseOK(200, null, 'Thay đổi mật khẩu thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function forgotSend(RequestSendForgot $request)
    {
        try {
            $email = $request->email;
            $findUser = User::where('email', $email)->first();
            if (empty($findUser)) {
                return $this->responseError(400, 'Không tìm thấy tài khoản trong hệ thống !');
            }

            $token = Str::random(32);
            $isUser = 1;
            $user = PasswordResetRepository::findPasswordReset($email, $isUser);
            if ($user) {
                // $data = ['token' => $token];
                // $user = PasswordResetRepository::updatePasswordReset($user, $data);
                $user->update(['token' => $token]);
            } else {
                PasswordResetRepository::createToken($email, $isUser, $token);
            }
            $url = UserEnum::FORGOT_FORM_USER . $token;
            Log::info("Add jobs to Queue , Email: $email with URL: $url");
            Queue::push(new SendForgotPasswordEmail($email, $url));

            return $this->responseOK(201, null, 'Gửi mail đặt lại mật khẩu thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function forgotUpdate(RequestCreatePassword $request)
    {
        try {
            $token = $request->token ?? '';
            $new_password = Hash::make($request->new_password);
            $passwordReset = PasswordResetRepository::findByToken($token);
            if ($passwordReset) {
                $user = UserRepository::findUserByEmail($passwordReset->email);
                if ($passwordReset->is_user == 1 && !empty($user)) {
                    $user->update(['password' => $new_password]);
                    $passwordReset->delete();

                    return $this->responseOK(200, null, 'Đặt lại mật khẩu thành công !');
                } else {
                    return $this->responseError(400, 'Không tìm thấy tài khoản !');
                }
            } else {
                return $this->responseError(400, 'Token đã hết hạn !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    // verify email
    public function verifyEmail(Request $request)
    {
        try {
            $token = $request->token ?? '';
            $user = $this->userRepository->findUserByTokenVerifyEmail($token);
            if ($user) {
                $data = [
                    'email_verified_at' => now(),
                    'token_verify_email' => null,
                ];
                $user = $this->userRepository->updateUser($user->id, $data);

                return $this->responseOK(200, null, 'Email của bạn đã được xác nhận !');
            } else {
                return $this->responseError(400, 'Token đã hết hạn !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function getInforUser($id)
    {
        try {
            $user = UserRepository::findUserById($id);
            if (empty($user)) {
                return $this->responseError(400, 'Không tìm thấy tài khoản !');
            }
            $inforUser = InforUserRepository::getInforUser(['id_user' => $user->id])->first();
            if ($user->role == 'hospital') {
                $inforUser = InforHospitalRepository::getInforHospital(['id_hospital' => $user->id])->first();
                $inforUser->infrastructure = json_decode($inforUser->infrastructure);
                $inforUser->location = json_decode($inforUser->location);
            }
            if ($user->role == 'doctor') {
                $inforUser = InforDoctorRepository::getInforDoctor(['id_doctor' => $user->id])->first();
            }
            $arrUser = array_merge($user->toArray(), $inforUser->toArray());

            return $this->responseOK(200, $arrUser, 'Xem thông tin người dùng thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }
}
