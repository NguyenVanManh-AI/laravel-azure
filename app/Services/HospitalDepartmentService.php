<?php

namespace App\Services;

use App\Http\Requests\RequestCreateHospitalDepartment;
use App\Http\Requests\RequestUpdateHospitalDepartment;
use App\Models\InforDoctor;
use App\Repositories\DepartmentRepository;
use App\Repositories\HospitalDepartmentInterface;
use App\Repositories\HospitalServiceRepository;
use App\Repositories\InforDoctorRepository;
use App\Repositories\InforHospitalRepository;
use Illuminate\Http\Request;
use Throwable;

class HospitalDepartmentService
{
    protected HospitalDepartmentInterface $hospitalDepartment;

    public function __construct(
        HospitalDepartmentInterface $hospitalDepartment
    ) {
        $this->hospitalDepartment = $hospitalDepartment;
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

    public function add(RequestCreateHospitalDepartment $request)
    {
        try {
            $user = auth()->guard('user_api')->user();
            $department = DepartmentRepository::findById($request->id_department);
            if (empty($department)) {
                return $this->responseError(400, 'Không tìm thấy khoa !');
            }

            $data = array_merge($request->all(), ['id_hospital' => $user->id]);
            $hospitalDepartment = $this->hospitalDepartment->createHosDepart($data);

            return $this->responseOK(201, $hospitalDepartment, 'Thêm khoa cho bệnh viện thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function edit(RequestUpdateHospitalDepartment $request, $id)
    {
        try {
            $user = auth()->guard('user_api')->user();
            $hospitalDepartment = $this->hospitalDepartment->findById($id);
            if (empty($hospitalDepartment)) {
                return $this->responseError(400, 'Không tìm thấy khoa này của bệnh viện !');
            }

            if ($user->id != $hospitalDepartment->id_hospital) {
                return $this->responseError(403, 'Bạn không có quyền chỉnh sửa !');
            }

            $hospitalDepartment = $this->hospitalDepartment->updateHospitalDepartment($hospitalDepartment, $request->all());

            return $this->responseOK(200, $hospitalDepartment, 'Cập nhật thông tin khoa cho bệnh viện thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $user = auth()->guard('user_api')->user();
            $hospitalDepartment = $this->hospitalDepartment->findById($id);
            if (empty($hospitalDepartment)) {
                return $this->responseError(400, 'Không tìm thấy khoa trong bệnh viện !');
            }

            if ($user->id != $hospitalDepartment->id_hospital) {
                return $this->responseError(403, 'Bạn không có quyền !');
            }

            // dịch vụ
            $count = HospitalServiceRepository::getHospitalService(['id_hospital_department' => $id])->count();
            if ($count > 0) {
                return $this->responseError(400, 'Khoa này đang có dịch vụ , bạn không được xóa nó !');
            }

            // bác sĩ thuộc khoa
            $count = InforDoctor::where('id_department', $hospitalDepartment->id_department)->count();
            if ($count > 0) {
                return $this->responseError(400, 'Khoa này đang có bác sĩ , bạn không được xóa nó !');
            }

            $hospitalDepartment->delete();

            return $this->responseOK(200, null, 'Xóa khoa của bệnh viện thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function departmentOfHospital(Request $request, $id)
    {
        try {
            $hospital = InforHospitalRepository::getInforHospital(['id_hospital' => $id])->first();
            if (empty($hospital)) {
                return $this->responseError(400, 'Không tìm thấy bệnh viện !');
            }

            $orderBy = $request->typesort ?? 'hospital_departments.id';
            switch ($orderBy) {
                case 'name':
                    $orderBy = 'departments.name';
                    break;

                case 'new':
                    $orderBy = 'hospital_departments.id';
                    break;

                default:
                    $orderBy = 'hospital_departments.id';
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
                'id_hospital' => $id,
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
            ];

            if (!(empty($request->paginate))) {
                $hospitalDepartments = $this->hospitalDepartment->searchHospitalDepartment($filter)->paginate($request->paginate);
            } else {
                $hospitalDepartments = $this->hospitalDepartment->searchHospitalDepartment($filter)->get();
            }

            return $this->responseOK(200, $hospitalDepartments, 'Xem tất cả khoa của bệnh viện thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function departmentOfHospitalSelect(Request $request, $id)
    {
        try {
            $hospital = InforHospitalRepository::getInforHospital(['id_hospital' => $id])->first();
            if (empty($hospital)) {
                return $this->responseError(400, 'Không tìm thấy bệnh viện !');
            }

            $orderBy = $request->typesort ?? 'hospital_departments.id';
            switch ($orderBy) {
                case 'name':
                    $orderBy = 'departments.name';
                    break;

                case 'new':
                    $orderBy = 'hospital_departments.id';
                    break;

                default:
                    $orderBy = 'hospital_departments.id';
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
                'id_hospital' => $id,
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
            ];

            if (!(empty($request->paginate))) {
                $hospitalDepartments = $this->hospitalDepartment->searchHospitalDepartment($filter)->paginate($request->paginate);
            } else {
                $hospitalDepartments = $this->hospitalDepartment->searchHospitalDepartment($filter)->get();
                $hospitalServicesOptimize = [];
                foreach ($hospitalDepartments as $hospitalDepartment) {
                    // loại bỏ đi các khoa không có bác sĩ
                    $filter = (object) [
                        'id_department' => $hospitalDepartment->id_department,
                        'id_hospital' => $hospitalDepartment->id_hospital,
                    ];
                    $n = InforDoctorRepository::getInforDoctor($filter)->count();
                    if ($n > 0) {
                        $hospitalServicesOptimize[] = $hospitalDepartment;
                    }
                }
                $hospitalDepartments = $hospitalServicesOptimize;
            }

            return $this->responseOK(200, $hospitalDepartments, 'Xem tất cả khoa của bệnh viện thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function details($id)
    {
        try {
            $filter = (object) [
                'id' => $id,
            ];
            $hospitalDepartment = $this->hospitalDepartment->searchHospitalDepartment($filter)->first();
            if (empty($hospitalDepartment)) {
                return $this->responseError(400, 'Không tìm thấy khoa trong bệnh viện !');
            }

            return $this->responseOK(200, $hospitalDepartment, 'Xem chi tiết khoa của bệnh viện thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }
}
