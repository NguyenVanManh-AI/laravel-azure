<?php

namespace App\Services;

use App\Http\Requests\RequestCreateHealthInsurance;
use App\Http\Requests\RequestUpdateHealthInsurance;
use App\Repositories\HealthInsuranceHospitalInterface;
use App\Repositories\HealthInsuranceInterface;
use Illuminate\Http\Request;
use Throwable;

class HealthInsuranceService
{
    protected HealthInsuranceInterface $healthInsurRepository;

    protected HealthInsuranceHospitalInterface $healInsurHosRepository;

    public function __construct(
        HealthInsuranceInterface $healthInsurRepository,
        HealthInsuranceHospitalInterface $healInsurHosRepository
    ) {
        $this->healthInsurRepository = $healthInsurRepository;
        $this->healInsurHosRepository = $healInsurHosRepository;
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

    public function add(RequestCreateHealthInsurance $request)
    {
        try {
            $healthInsurance = $this->healthInsurRepository->createHealthInsur($request->all());

            return $this->responseOK(201, $healthInsurance, 'Thêm bảo hiểm thành công thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function edit(RequestUpdateHealthInsurance $request, $id)
    {
        try {
            $healthInsurance = $this->healthInsurRepository->findById($id);
            if ($healthInsurance) {
                $healthInsurance = $this->healthInsurRepository->updateHealthInsur($healthInsurance, $request->all());

                return $this->responseOK(200, $healthInsurance, 'Cập nhật thông tin bảo hiểm thành công !');
            } else {
                return $this->responseError(404, 'Không tìm thấy bảo hiểm !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $healthInsurance = $this->healthInsurRepository->findById($id);
            if ($healthInsurance) {
                $healInsurHos = $this->healInsurHosRepository->getHealInsurHos(['id_health_insurance' => $id]);
                $healInsurHos->delete();
                $healthInsurance->delete();

                return $this->responseOK(200, null, 'Xóa bảo hiểm thành công !');
            } else {
                return $this->responseError(404, 'Không tìm thấy bảo hiểm !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function all(Request $request)
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
                'search' => $search ?? '',
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
            ];

            if (!(empty($request->paginate))) {
                $departments = $this->healthInsurRepository->searchHealthInsur($filter)->paginate($request->paginate);
            } else {
                $departments = $this->healthInsurRepository->searchHealthInsur($filter)->get();
            }

            return $this->responseOK(200, $departments, 'Xem tất cả bảo hiểm thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function details(Request $request, $id)
    {
        try {
            $department = $this->healthInsurRepository->findById($id);
            if ($department) {
                return $this->responseOK(200, $department, 'Xem chi tiết bảo hiểm thành công !');
            } else {
                return $this->responseError(404, 'Không tìm thấy bảo hiểm !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }
}
