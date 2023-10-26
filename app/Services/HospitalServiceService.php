<?php

namespace App\Services;

use App\Http\Requests\RequestCreateHospitalService;
use App\Http\Requests\RequestUpdateHospitalService;
use App\Models\WorkSchedule;
use App\Repositories\HospitalDepartmentRepository;
use App\Repositories\HospitalServiceInterface;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Throwable;

class HospitalServiceService
{
    protected HospitalServiceInterface $hospitalService;

    public function __construct(HospitalServiceInterface $hospitalService)
    {
        $this->hospitalService = $hospitalService;
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

    public function add(RequestCreateHospitalService $request)
    {
        try {
            $user = auth()->guard('user_api')->user();
            $filter = [
                'id' => $request->id_hospital_department,
                'id_hospital' => $user->id,
            ];
            $hospitalDepartment = HospitalDepartmentRepository::getHospitalDepartment($filter)->first();

            if (empty($hospitalDepartment)) {
                return $this->responseError(404, 'Không tìm thấy khoa trong bệnh viện !');
            }

            $request->merge(['infor' => json_encode($request->infor)]);
            $hospitalService = $this->hospitalService->createHospitalService($request->all());
            $hospitalService->infor = json_decode($hospitalService->infor);

            return $this->responseOK(201, $hospitalService, 'Thêm dịch vụ cho bệnh viện thành công ! ');
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function edit(RequestUpdateHospitalService $request, $id)
    {
        try {
            $user = auth()->guard('user_api')->user();
            $hospitalService = $this->hospitalService->findById($id);
            if (empty($hospitalService)) {
                return $this->responseError(404, 'Không tìm thấy dịch vụ trong bệnh viện !');
            }

            $filter = [
                'id' => $request->id_hospital_department,
                'id_hospital' => $user->id,
            ];
            $hospitalDepartment = HospitalDepartmentRepository::getHospitalDepartment($filter)->first();
            if (empty($hospitalDepartment)) {
                return $this->responseError(404, 'Không tìm thấy khoa trong bệnh viện !');
            }
            $request->merge(['infor' => json_encode($request->infor)]);
            $hospitalService = $this->hospitalService->updateHospitalService($hospitalService, $request->all());
            $hospitalService->infor = json_decode($hospitalService->infor);

            return $this->responseOK(200, $hospitalService, 'Cập nhật dịch vụ thành công !');
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function delete($id)
    {
        try {
            $user = auth()->guard('user_api')->user();
            $hospitalService = $this->hospitalService->findById($id);
            if ($hospitalService) {
                $filter = [
                    'id' => $hospitalService->id_hospital_department,
                    'id_hospital' => $user->id,
                ];
                $hospitalDepartment = HospitalDepartmentRepository::getHospitalDepartment($filter)->first();
                if (empty($hospitalDepartment)) {
                    return $this->responseError(403, 'Bạn không có quyền xóa !');
                }
                // kiểm tra có workSchedule có id_service là nó không
                // nếu có thì workSchedule đã được làm chưa (time của workSchedule nhỏ hơn thời gian hiện tại là được)
                // sau đó mới xóa . tạm thời cứ check có hay chưa đã
                // khi nào làm đến bảng WorkSchedule thì quay lại
                $count = WorkSchedule::where('id_service', $hospitalService->id)->count();

                if ($count > 0) {
                    return $this->responseError(400, 'Dịch vụ này đang được đặt , bạn không được xóa nó !');
                }
                $hospitalService->delete();

                return $this->responseOK(200, null, 'Xóa dịch vụ thành công !');
            } else {
                return $this->responseError(404, 'Không tìm thấy dịch vụ !');
            }
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function serviceOfHospital(Request $request, $id)
    {
        try {
            $user = UserRepository::findUserById($id);
            if (empty($user)) {
                return $this->responseError(404, 'Không tìm thấy bệnh viện !');
            }

            $search = $request->search;
            $orderBy = 'id_hospital_service';
            $orderDirection = 'ASC';

            if ($request->sortlatest == 'true') {
                $orderBy = 'id_hospital_service';
                $orderDirection = 'DESC';
            }

            if ($request->sortname == 'true') {
                $orderBy = 'hospital_services.name';
                $orderDirection = ($request->sortlatest == 'true') ? 'DESC' : 'ASC';
            }

            $filter = (object) [
                'search' => $search,
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
                'id_hospital' => $user->id,
            ];

            if (!empty($request->paginate)) {
                $hospitalServices = $this->hospitalService->searchAll($filter)->paginate($request->paginate);
                foreach ($hospitalServices as $index => $hospitalService) {
                    $hospitalService->infor = json_decode($hospitalService->infor);
                }
            } else { // all
                $hospitalServices = $this->hospitalService->searchAll($filter)->get();
                foreach ($hospitalServices as $index => $hospitalService) {
                    $hospitalService->infor = json_decode($hospitalService->infor);
                }
            }

            return $this->responseOK(200, $hospitalServices, 'Xem tất cả dịch vụ của bệnh viện thành công !');
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function details(Request $request, $id)
    {
        try {
            $filter = (object) [
                'id_hospital_services' => $id,
            ];
            $hospitalServices = $this->hospitalService->searchAll($filter)->first();
            if ($hospitalServices) {
                $hospitalServices->infor = json_decode($hospitalServices->infor);

                return $this->responseOK(200, $hospitalServices, 'Xem dịch vụ chi tiết thành công !');
            } else {
                return $this->responseError(404, 'Không tìm thấy dịch vụ trong bệnh viện !');
            }
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function all(Request $request)
    {
        try {
            $search = $request->search;
            $orderBy = 'id_hospital_service';
            $orderDirection = 'ASC';

            if ($request->sortlatest == 'true') {
                $orderBy = 'id_hospital_service';
                $orderDirection = 'DESC';
            }

            if ($request->sortname == 'true') {
                $orderBy = 'name';
                $orderDirection = ($request->sortlatest == 'true') ? 'DESC' : 'ASC';
            }

            $filter = (object) [
                'search' => $search,
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
            ];

            if (!empty($request->paginate)) {
                $hospitalServices = $this->hospitalService->searchAll($filter)->paginate($request->paginate);
            } else {
                $hospitalServices = $this->hospitalService->searchAll($filter)->get();
            }

            foreach ($hospitalServices as $index => $hospitalService) {
                $hospitalService->infor = json_decode($hospitalService->infor);
            }

            return $this->responseOK(200, $hospitalServices, 'Xem tất cả dịch vụ thành công !');
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
