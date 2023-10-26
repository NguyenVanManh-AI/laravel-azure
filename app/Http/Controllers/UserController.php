<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestChangePassword;
use App\Http\Requests\RequestCreatePassword;
use App\Http\Requests\RequestSendForgot;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(Request $request)
    {
        return $this->userService->login($request);
    }

    public function logout()
    {
        auth('user_api')->logout();

        return response()->json([
            'message' => 'Đăng xuất thành công !',
            'status' => 200,
        ], 200);
    }

    public function changePassword(RequestChangePassword $request)
    {
        return $this->userService->changePassword($request);
    }

    public function forgotForm(Request $request)
    {
        return view('user.reset_password');
    }

    public function forgotSend(RequestSendForgot $request)
    {
        return $this->userService->forgotSend($request);
    }

    public function forgotUpdate(RequestCreatePassword $request)
    {
        return $this->userService->forgotUpdate($request);
    }

    // verify email
    public function verifyEmail($token)
    {
        return $this->userService->verifyEmail($token);
    }

    public function getInforUser($id)
    {
        return $this->userService->getInforUser($id);
    }
}
