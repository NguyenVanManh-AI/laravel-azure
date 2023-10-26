<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestChangePassword;
use App\Http\Requests\RequestChangeRole;
use App\Http\Requests\RequestCreateNewAdmin;
use App\Http\Requests\RequestCreatePassword;
use App\Http\Requests\RequestSendForgot;
use App\Http\Requests\RequestUpdateAdmin;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function login(Request $request)
    {
        return $this->adminService->login($request);
    }

    public function profile()
    {
        return response()->json([
            'message' => 'Xem thông tin cá nhân thành công !',
            'data' => auth('admin_api')->user(),
            'status' => 200,
        ], 200);
    }

    public function logout()
    {
        auth('admin_api')->logout();

        return response()->json([
            'message' => 'Đăng xuất thành công !',
            'status' => 200,
        ], 200);
    }

    public function changePassword(RequestChangePassword $request)
    {
        return $this->adminService->changePassword($request);
    }

    public function saveAvatar(Request $request)
    {
        return $this->adminService->saveAvatar($request);
    }

    public function updateProfile(RequestUpdateAdmin $request)
    {
        return $this->adminService->updateProfile($request);
    }

    // verify email
    public function verifyEmail($token)
    {
        return $this->adminService->verifyEmail($token);
    }

    public function allAdmin(Request $request)
    {
        return $this->adminService->allAdmin($request);
    }

    public function allUser(Request $request)
    {
        return $this->adminService->allUser($request);
    }

    public function forgotForm()
    {
        return view('admin.reset_password');
    }

    public function forgotSend(RequestSendForgot $request)
    {
        return $this->adminService->forgotSend($request);
    }

    public function forgotUpdate(RequestCreatePassword $request)
    {
        return $this->adminService->forgotUpdate($request);
    }

    public function addAdmin(RequestCreateNewAdmin $request)
    {
        return $this->adminService->addAdmin($request);
    }

    public function deleteAdmin($id)
    {
        return $this->adminService->deleteAdmin($id);
    }

    public function editRole(RequestChangeRole $request, $id)
    {
        return $this->adminService->editRole($request, $id);
    }

    public function changeAccept(Request $request, $id)
    {
        return $this->adminService->changeAccept($request, $id);
    }
}
