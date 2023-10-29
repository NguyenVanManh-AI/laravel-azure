<?php

namespace App\Services;

use App\Http\Requests\RequestCreateDepartment;
use App\Http\Requests\RequestUpdateDepartment;
use App\Models\Department;
use App\Repositories\DepartmentInterface;
use App\Repositories\DepartmentRepository;
use App\Repositories\HospitalDepartmentRepository;
use App\Repositories\InforDoctorRepository;
use App\Repositories\InforHospitalRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Throwable;

class DepartmentService
{
    protected DepartmentInterface $departmentRepository;

    public function __construct(DepartmentInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
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
        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_department_' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/image/thumbnail/departments/', $filename);

            return 'storage/image/thumbnail/departments/' . $filename;
        }
    }

    public function add(RequestCreateDepartment $request)
    {
        try {
            $department = $this->departmentRepository->createDepartment($request->all());
            $thumbnail = $this->saveAvatar($request);

            $data = ['thumbnail' => $thumbnail];
            $department = $this->departmentRepository->updateDepartment($department, $data);

            return $this->responseOK(201, $department, 'Thêm khoa thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function edit(RequestUpdateDepartment $request, $id)
    {
        try {
            $department = $this->departmentRepository->findById($id);
            if ($department) {
                if ($request->hasFile('thumbnail')) {
                    if ($department->thumbnail) {
                        File::delete($department->thumbnail);
                    }
                    $thumbnail = $this->saveAvatar($request);
                    $data = array_merge($request->all(), ['thumbnail' => $thumbnail]);
                    $department = $this->departmentRepository->updateDepartment($department, $data);
                } else {
                    $department = $this->departmentRepository->updateDepartment($department, $request->all());
                }

                return $this->responseOK(200, $department, 'Cập nhật thông tin khoa thành công !');
            } else {
                return $this->responseError(404, 'Không tìm thấy khoa !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    // Không nên xóa khoa , chỉ chỉnh sửa thôi
    public function delete($id)
    {
        try {
            $department = $this->departmentRepository->findById($id);
            if ($department) {
                $doctors = InforDoctorRepository::getInforDoctor(['id_department' => $id])->count();
                $hospitalDepartment = HospitalDepartmentRepository::getHospitalDepartment(['id_department' => $id])->count();
                if ($doctors > 0) {
                    return $this->responseError(400, 'Không được xóa . Đang có bác sĩ thuộc khoa này !');
                }
                if ($hospitalDepartment > 0) {
                    return $this->responseError(400, 'Không được xóa . Đang có bệnh viện chứa khoa này !');
                }
                // InforDoctorRepository::updateResult($doctors, ['id_department' => null]);
                // HospitalDepartmentRepository::updateHospitalDepartment($hospitalDepartment, ['id_department' => null]);
                if ($department->thumbnail) {
                    File::delete($department->thumbnail);
                }
                $department->delete();

                return $this->responseOK(200, null, 'Xóa khoa thành công !');
            } else {
                return $this->responseError(404, 'Không tìm thấy khoa !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function all(Request $request)
    {
        try {
            // hospital
            if ($request->id_hospital) {
                $hospital = InforHospitalRepository::getInforHospital(['id_hospital' => $request->id_hospital])->first();
                if (empty($hospital)) {
                    return $this->responseError(404, 'Không tìm thấy bệnh viện !');
                }
                $hospitalDepartments = HospitalDepartmentRepository::searchHospitalDepartment(['id_hospital' => $request->id_hospital])->get();
                $id_departments = [];
                foreach ($hospitalDepartments as $hospitalDepartment) {
                    $id_departments[] = $hospitalDepartment->id_department;
                }
            }

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
                'search' => $search ?? '',
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
                'id_departments' => $id_departments ?? [0],
            ];
            if (!(empty($request->paginate))) {
                $departments = $this->departmentRepository->searchDepartment($filter)->paginate($request->paginate);
            } else {
                $departments = $this->departmentRepository->searchDepartment($filter)->get();
            }

            return $this->responseOK(200, $departments, 'Xem tất cả khoa thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function details(Request $request, $id)
    {
        try {
            $department = $this->departmentRepository->findById($id);
            if ($department) {
                // search number
                $search_number = $department->search_number + 1;
                $department = DepartmentRepository::updateDepartment($department, ['search_number' => $search_number]);
                // search number

                return $this->responseOK(200, $department, 'Xem chi tiết khoa thành công !');
            } else {
                return $this->responseError(404, 'Không tìm thấy khoa !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }
}
