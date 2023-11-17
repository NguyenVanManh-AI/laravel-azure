<?php

namespace App\Services;

use App\Http\Requests\RequestCreateHospitalService;
use App\Http\Requests\RequestStatusService;
use App\Http\Requests\RequestUpdateHospitalService;
use App\Models\HospitalService;
use App\Models\Rating;
use App\Models\WorkSchedule;
use App\Repositories\HospitalDepartmentRepository;
use App\Repositories\HospitalServiceInterface;
use App\Repositories\InforDoctorRepository;
use App\Repositories\RatingRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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

    public function saveThumbnail(Request $request)
    {
        if ($request->hasFile('thumbnail_service')) {
            $image = $request->file('thumbnail_service');
            $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_service_' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/image/thumbnail/services/', $filename);

            return 'storage/image/thumbnail/services/' . $filename;
        }
    }

    public function add(RequestCreateHospitalService $request)
    {
        try {
            $user = auth()->guard('user_api')->user();
            $filter = [
                'id' => $request->id_hospital_department, // tìm id id_hospital_department (này lấy ra được từ selection những chuyên khoa của bệnh viện)
                'id_hospital' => $user->id, // để xem có phải là của hospital này không
            ];
            $hospitalDepartment = HospitalDepartmentRepository::getHospitalDepartment($filter)->first();

            if (empty($hospitalDepartment)) {
                return $this->responseError(400, 'Không tìm thấy khoa trong bệnh viện !');
            }

            // lúc lưu vào thì id_hospital_department của bảng sẽ là id của bảng ghi trong bảng hospital_department (chứ không phải là id_department)
            // vì chỉ cần lấy ra được id của hospital_department là lấy ra được nó của bệnh viện nào và khoa gì
            $thumbnail_service = $this->saveThumbnail($request);
            $data = array_merge(
                $request->all(),
                [
                    'infor' => json_encode($request->infor),
                    'is_delete' => 0,
                    'thumbnail_service' => $thumbnail_service,
                ]
            );
            $hospitalService = $this->hospitalService->createHospitalService($data);
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
                return $this->responseError(400, 'Không tìm thấy dịch vụ trong bệnh viện !');
            }

            $filter = [
                'id' => $request->id_hospital_department,
                'id_hospital' => $user->id,
            ];
            $hospitalDepartment = HospitalDepartmentRepository::getHospitalDepartment($filter)->first();
            if (empty($hospitalDepartment)) {
                return $this->responseError(400, 'Không tìm thấy khoa trong bệnh viện !');
            }

            if ($request->hasFile('thumbnail_service')) {
                if ($hospitalService->thumbnail_service) {
                    File::delete($hospitalService->thumbnail_service);
                }
                $thumbnail_service = $this->saveThumbnail($request);
                $data = array_merge(
                    $request->all(),
                    [
                        'thumbnail_service' => $thumbnail_service,
                        'infor' => json_encode($request->infor),
                    ]
                );
                $hospitalService = $this->hospitalService->updateHospitalService($hospitalService, $data);
            } else {
                $data = array_merge(
                    $request->all(),
                    [
                        'thumbnail_service' => $hospitalService->thumbnail_service,
                        'infor' => json_encode($request->infor),
                    ]
                );
                $hospitalService = $this->hospitalService->updateHospitalService($hospitalService, $data);
            }
            $hospitalService->infor = json_decode($hospitalService->infor);

            return $this->responseOK(200, $hospitalService, 'Cập nhật dịch vụ thành công !');
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function changeStatus(RequestStatusService $request)
    {
        try {
            $id_service = $request->id_service;
            $is_delete = $request->is_delete;
            $user = auth()->guard('user_api')->user();
            $hospitalService = $this->hospitalService->findById($id_service);
            if ($hospitalService) {
                $filter = [
                    'id' => $hospitalService->id_hospital_department,
                    'id_hospital' => $user->id,
                ];
                $hospitalDepartment = HospitalDepartmentRepository::getHospitalDepartment($filter)->first();
                if (empty($hospitalDepartment)) {
                    return $this->responseError(403, 'Bạn không có quyền xóa !');
                }

                $hospitalService->update(['is_delete' => $is_delete]);
                $message = 'Xóa dịch vụ thành công !';
                if ($is_delete == 0) {
                    $message = 'Khôi phục dịch vụ thành công !';
                }

                return $this->responseOK(200, null, $message);
            } else {
                return $this->responseError(400, 'Không tìm thấy dịch vụ !');
            }
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function hospitalManage(Request $request)
    {
        try {
            $user = auth()->guard('user_api')->user();

            $orderBy = $request->typesort ?? 'id_hospital_service';
            switch ($orderBy) {
                case 'name':
                    $orderBy = 'hospital_services.name';
                    break;

                case 'new':
                    $orderBy = 'id_hospital_service';
                    break;

                case 'search_number':
                    $orderBy = 'hospital_services.search_number_service';
                    break;

                default:
                    $orderBy = 'id_hospital_service';
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
                'id_hospital' => $user->id,
                'is_delete' => $request->is_delete ?? null, // null = all ; 1 = đã xóa ; 0 = chưa xóa
            ];

            if (!empty($request->paginate)) {
                $hospitalServices = $this->hospitalService->searchAll($filter)->paginate($request->paginate);
            } else {
                $hospitalServices = $this->hospitalService->searchAll($filter)->get();
            }

            foreach ($hospitalServices as $index => $service) {
                $service->infor = json_decode($service->infor);

                // rating
                $workSchedules = WorkSchedule::where('id_service', $service->id_hospital_service)->get();
                $cout_rating = 0;
                $sum_rating = 0;
                $service->cout_rating = 0;
                $service->number_rating = 0;
                if (count($workSchedules) > 0) {
                    foreach ($workSchedules as $index => $workSchedule) {
                        $rating = Rating::where('id_work_schedule', $workSchedule->id)->first();
                        if (!empty($rating)) {
                            $cout_rating += 1;
                            $sum_rating += $rating->number_rating;
                        }
                    }
                    $service->cout_rating = $cout_rating;
                    $service->number_rating = ($cout_rating != 0) ? round($sum_rating / $cout_rating, 1) : 0;
                }
            }

            return $this->responseOK(200, $hospitalServices, 'Xem tất cả dịch vụ của bệnh viện thành công !');
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function serviceOfHospital(Request $request, $id)
    {
        try {
            $user = UserRepository::findUserById($id);
            if (empty($user)) {
                return $this->responseError(400, 'Không tìm thấy bệnh viện !');
            }

            $orderBy = $request->typesort ?? 'id_hospital_service';
            switch ($orderBy) {
                case 'name':
                    $orderBy = 'hospital_services.name';
                    break;

                case 'new':
                    $orderBy = 'id_hospital_service';
                    break;

                default:
                    $orderBy = 'id_hospital_service';
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
                'id_hospital' => $user->id,
                'is_delete' => 0,
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

            // rating
            foreach ($hospitalServices as $index => $service) {
                $workSchedules = WorkSchedule::where('id_service', $service->id_hospital_service)->get();
                $cout_rating = 0;
                $sum_rating = 0;
                $service->cout_rating = 0;
                $service->number_rating = 0;
                if (count($workSchedules) > 0) {
                    foreach ($workSchedules as $index => $workSchedule) {
                        $rating = Rating::where('id_work_schedule', $workSchedule->id)->first();
                        if (!empty($rating)) {
                            $cout_rating += 1;
                            $sum_rating += $rating->number_rating;
                        }
                    }
                    $service->cout_rating = $cout_rating;
                    $service->number_rating = ($cout_rating != 0) ? round($sum_rating / $cout_rating, 1) : 0;
                }
            }

            return $this->responseOK(200, $hospitalServices, 'Xem tất cả dịch vụ của bệnh viện thành công !');
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function serviceOfHospitalSelect(Request $request, $id)
    {
        try {
            $user = UserRepository::findUserById($id);
            if (empty($user)) {
                return $this->responseError(400, 'Không tìm thấy bệnh viện !');
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
                'is_delete' => 0,
            ];

            if (!empty($request->paginate)) {
                $hospitalServices = $this->hospitalService->searchAll($filter)->paginate($request->paginate);
                foreach ($hospitalServices as $index => $hospitalService) {
                    $hospitalService->infor = json_decode($hospitalService->infor);
                }
            } else { // all
                $hospitalServices = $this->hospitalService->searchAll($filter)->get();

                $hospitalServicesOptimize = [];
                foreach ($hospitalServices as $index => $hospitalService) {
                    $hospitalService->infor = json_decode($hospitalService->infor);

                    // loại bỏ đi các dịch vụ không có bác sĩ
                    $filter = (object) [
                        'id_department' => $hospitalService->id_department,
                        'id_hospital' => $hospitalService->id_hospital,
                    ];
                    $n = InforDoctorRepository::getInforDoctor($filter)->count();
                    if ($n > 0) {
                        $hospitalServicesOptimize[] = $hospitalService;
                    }
                }

                $hospitalServices = $hospitalServicesOptimize;
            }

            return $this->responseOK(200, $hospitalServices, 'Xem tất cả dịch vụ của bệnh viện thành công !');
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function detailManage(Request $request, $id)
    {
        try {
            $request->merge(['page' => 1]); // tránh cho việc người dùng cho page = 2 thì nó sẽ ảnh hưởng đến
            // phân trang ở ratings ở dưới
            $filter = (object) [
                'id_hospital_services' => $id,
            ];
            $hospitalServices = $this->hospitalService->searchAll($filter)->first();
            if ($hospitalServices) {
                $hospitalServices->infor = json_decode($hospitalServices->infor);

                // ratings
                $workSchedules = WorkSchedule::where('id_service', $hospitalServices->id_hospital_service)->get();
                $idWorkSchedules = $workSchedules->pluck('id')->toArray();

                $cout_rating = 0;
                $sum_rating = 0;
                $hospitalServices->cout_rating = 0;
                $hospitalServices->number_rating = 0;
                $hospitalServices->cout_details = null;
                $hospitalServices->ratings = null;
                if (count($workSchedules) > 0) {
                    $filter = (object) [
                        'list_id_work_schedule' => $idWorkSchedules,
                    ];
                    $ratings = RatingRepository::getRating($filter)->paginate(6);
                    $hospitalServices->ratings = $ratings;

                    $cout_details = [];
                    for ($i = 1; $i <= 5; $i++) {
                        $filter->number_rating = $i;
                        // $cout_detail[] = RatingRepository::getRating($filter)->count();
                        $cout_details["{$i}_star"] = RatingRepository::getRating($filter)->count();
                    }
                    $hospitalServices->cout_details = $cout_details;

                    foreach ($cout_details as $key => $count) {
                        $rating = (int) $key; // Convert the key to an integer
                        $cout_rating += $count;
                        $sum_rating += $rating * $count;
                    }

                    // foreach ($workSchedules as $index => $workSchedule) {
                    //     $rating = Rating::where('id_work_schedule', $workSchedule->id)->first();
                    //     if(!empty($rating)) {
                    //         $cout_rating += 1;
                    //         $sum_rating += $rating->number_rating;
                    //     }
                    // }
                    $hospitalServices->cout_rating = $cout_rating;
                    $hospitalServices->number_rating = ($cout_rating != 0) ? round($sum_rating / $cout_rating, 1) : 0;
                }

                return $this->responseOK(200, $hospitalServices, 'Xem dịch vụ chi tiết thành công !');
            } else {
                return $this->responseError(400, 'Không tìm thấy dịch vụ trong bệnh viện !');
            }
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function details(Request $request, $id)
    {
        try {
            $request->merge(['page' => 1]); // tránh cho việc người dùng cho page = 2 thì nó sẽ ảnh hưởng đến
            // phân trang ở ratings ở dưới
            $filter = (object) [
                'id_hospital_services' => $id,
                'is_delete' => 0,
            ];
            $hospitalServices = $this->hospitalService->searchAll($filter)->first();
            if ($hospitalServices) {
                $hospitalServices->infor = json_decode($hospitalServices->infor);

                // ratings
                $workSchedules = WorkSchedule::where('id_service', $hospitalServices->id_hospital_service)->get();
                $idWorkSchedules = $workSchedules->pluck('id')->toArray();

                $cout_rating = 0;
                $sum_rating = 0;
                $hospitalServices->cout_rating = 0;
                $hospitalServices->number_rating = 0;
                $hospitalServices->cout_details = null;
                $hospitalServices->ratings = null;
                if (count($workSchedules) > 0) {
                    $filter = (object) [
                        'list_id_work_schedule' => $idWorkSchedules,
                    ];
                    $ratings = RatingRepository::getRating($filter)->paginate(6);
                    $hospitalServices->ratings = $ratings;

                    $cout_details = [];
                    for ($i = 1; $i <= 5; $i++) {
                        $filter->number_rating = $i;
                        $cout_details["{$i}_star"] = RatingRepository::getRating($filter)->count();
                    }
                    $hospitalServices->cout_details = $cout_details;

                    foreach ($cout_details as $key => $count) {
                        $rating = (int) $key;
                        $cout_rating += $count;
                        $sum_rating += $rating * $count;
                    }
                    $hospitalServices->cout_rating = $cout_rating;
                    $hospitalServices->number_rating = ($cout_rating != 0) ? round($sum_rating / $cout_rating, 1) : 0;
                }

                // tăng tìm kiếm cho service
                $hospitalServices->search_number_service += 1;
                $hospital_service = HospitalService::find($id);
                $hospital_service->update(['search_number_service' => $hospital_service->search_number_service + 1]);
                // tăng tìm kiếm cho service

                return $this->responseOK(200, $hospitalServices, 'Xem dịch vụ chi tiết thành công !');
            } else {
                return $this->responseError(400, 'Không tìm thấy dịch vụ trong bệnh viện !');
            }
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function moreRating(Request $request, $id_service)
    {
        try {
            $filter = (object) [
                'id_hospital_services' => $id_service,
                'is_delete' => 0,
            ];
            $hospitalServices = $this->hospitalService->searchAll($filter)->first();
            if ($hospitalServices) {
                $moreRating = (object) [];

                // ratings
                $workSchedules = WorkSchedule::where('id_service', $hospitalServices->id_hospital_service)->get();
                $idWorkSchedules = $workSchedules->pluck('id')->toArray();

                $cout_rating = 0;
                $sum_rating = 0;
                $moreRating->cout_rating = 0;
                $moreRating->number_rating = 0;
                $moreRating->cout_details = null;
                $moreRating->ratings = null;
                if (count($workSchedules) > 0) {
                    $filter = (object) [
                        'list_id_work_schedule' => $idWorkSchedules,
                    ];

                    $page = $request->page;
                    $ratings = RatingRepository::getRating($filter)->paginate(6);

                    $moreRating->ratings = $ratings;

                    $cout_details = [];
                    for ($i = 1; $i <= 5; $i++) {
                        $filter->number_rating = $i;
                        $cout_details["{$i}_star"] = RatingRepository::getRating($filter)->count();
                    }
                    $moreRating->cout_details = $cout_details;

                    foreach ($cout_details as $key => $count) {
                        $rating = (int) $key;
                        $cout_rating += $count;
                        $sum_rating += $rating * $count;
                    }
                    $moreRating->cout_rating = $cout_rating;
                    $moreRating->number_rating = ($cout_rating != 0) ? round($sum_rating / $cout_rating, 1) : 0;
                }

                return $this->responseOK(200, $moreRating, 'Xem đánh giá chi tiết thành công !');
            } else {
                return $this->responseError(400, 'Không tìm thấy dịch vụ trong bệnh viện !');
            }
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function all(Request $request)
    {
        try {
            $orderBy = $request->typesort ?? 'id_hospital_service';
            switch ($orderBy) {
                case 'name':
                    $orderBy = 'name';
                    break;

                case 'new':
                    $orderBy = 'id_hospital_service';
                    break;

                default:
                    $orderBy = 'id_hospital_service';
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
                'is_delete' => 0,
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
