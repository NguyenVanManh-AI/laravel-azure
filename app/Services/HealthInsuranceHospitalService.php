<?php

namespace App\Services;

use App\Http\Requests\RequestCreateHealthInsuranceHospital;
use App\Models\HealthInsuranceHospital;
use App\Repositories\HealthInsuranceHospitalInterface;
use App\Repositories\HealthInsuranceRepository;
use App\Repositories\InforHospitalRepository;
use Illuminate\Http\Request;
use Throwable;

class HealthInsuranceHospitalService
{
    protected HealthInsuranceHospitalInterface $healInsurHosRepository;

    public function __construct(HealthInsuranceHospitalInterface $healInsurHosRepository)
    {
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

    public function add(RequestCreateHealthInsuranceHospital $request)
    {
        try {
            $user = auth()->guard('user_api')->user();
            $healthInsurance = HealthInsuranceRepository::findById($request->id_health_insurance);
            if (empty($healthInsurance)) {
                return $this->responseError(400, 'Không tìm thấy bảo hiểm !');
            }

            $data = [
                'id_hospital' => $user->id,
                'id_health_insurance' => $request->id_health_insurance,
            ];
            $healInsurHos = $this->healInsurHosRepository->createHealInsurHos($data);

            return $this->responseOK(201, $healInsurHos, 'Chấp nhận bảo hiểm thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $user = auth()->guard('user_api')->user();
            $filter = [
                'id' => $id,
                'id_hospital' => $user->id,
            ];
            $healInsurHos = $this->healInsurHosRepository->getHealInsurHos($filter)->first();
            if (empty($healInsurHos)) {
                return $this->responseError(400, 'Không có bảo hiểm này trong danh sách chấp thuận !');
            }
            $healInsurHos->delete();

            return $this->responseOK(200, null, 'Bệnh viện hủy chấp thuận thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function deleteMany(Request $request)
    {
        try {
            $user = auth()->guard('user_api')->user();
            $list_id = $request->list_id ?? [0];
            HealthInsuranceHospital::whereIn('id', $list_id)->where('id_hospital', $user->id)->delete();

            return $this->responseOK(200, null, 'Hủy nhiều bảo hiểm thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function ofHospital(Request $request, $id)
    {
        try {
            $orderBy = $request->typesort ?? 'health_insurance_hospitals.id';
            switch ($orderBy) {
                case 'name':
                    $orderBy = 'health_insurances.name';
                    break;

                case 'new':
                    $orderBy = 'health_insurance_hospitals.id';
                    break;

                default:
                    $orderBy = 'health_insurance_hospitals.id';
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
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
                'id_hospital' => $id,
            ];

            if (!empty($request->paginate)) {
                $hospital = InforHospitalRepository::getInforHospital(['id_hospital' => $id])->first();
                if (empty($hospital)) {
                    return $this->responseError(400, 'Không tìm thấy bệnh viện !');
                }
                $healInsurHos = $this->healInsurHosRepository->searchHealInsurHos($filter)->paginate($request->paginate);

                return $this->responseOK(200, $healInsurHos, 'Xem tất cả bảo hiểm của bệnh viện thành công !');
            } else {
                $healInsurHos = $this->healInsurHosRepository->searchHealInsurHos($filter)->get();

                return $this->responseOK(200, $healInsurHos, 'Xem tất cả bảo hiểm của bệnh viện thành công !');
            }
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
            $healInsurHos = $this->healInsurHosRepository->searchHealInsurHos($filter)->first();
            if (empty($healInsurHos)) {
                return $this->responseError(400, 'Không có bảo hiểm này trong danh sách chấp thuận !');
            }

            return $this->responseOK(200, $healInsurHos, 'Xem chi tiết bảo hiểm thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }
}
