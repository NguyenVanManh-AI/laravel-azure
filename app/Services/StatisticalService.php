<?php

namespace App\Services;

use App\Http\Requests\RequestStatistical;
use App\Models\Article;
use App\Models\HospitalService;
use App\Models\User;
use App\Models\WorkSchedule;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\InforDoctorRepository;
use App\Repositories\InforHospitalRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkScheduleRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class StatisticalService
{
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

    /*
        ->whereDate('articles.created_at','>=', $startDate)
        ->whereDate('articles.created_at','<=', $endDate)

        => Lưu ý . Toàn bộ đều phải tách ra dùng WhereDate , thay vì dùng
        whereBetween('work_schedules.created_at', [$startDate, $endDate])

        => vì khi mà $startDate = $endDate thì nó sẽ không lấy ra gì cả
        (không có ngày nào nằm giữa hai ngày đó)
    */

    // admin
    public function statisticalUser($request, $role)
    {
        if ($request->start_date) {
            $startDate = Carbon::parse($request->start_date);
        } else {
            $startDate = User::orderBy('created_at', 'asc')->value('created_at');
        }

        if ($request->end_date) {
            $endDate = Carbon::parse($request->end_date);
        } else {
            $endDate = Carbon::now();
        }

        $userData = User::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('role', 'LIKE', '%' . $role . '%')
            ->groupBy('date')
            ->orderBy('date')
            ->get([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(id) as user_count'),
            ]);
        $result = $userData->pluck('user_count', 'date')->toArray();

        $dates = [];
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->toDateString();
        }

        foreach ($dates as $date) {
            if (array_key_exists($date, $result)) {
                $result[$date] = $result[$date];
            } else {
                $result[$date] = 0;
            }
        }
        ksort($result);

        return $result;
    }

    public function user(RequestStatistical $request)
    {
        try {
            $user = $this->statisticalUser($request, 'user');
            $doctor = $this->statisticalUser($request, 'doctor');
            $hospital = $this->statisticalUser($request, 'hospital');
            $total = $this->statisticalUser($request, '');

            $data = (object) [];
            $totalUser = [
                'user' => User::where('role', 'user')->count(),
                'doctor' => User::where('role', 'doctor')->count(),
                'hospital' => User::where('role', 'hospital')->count(),
            ];
            $data->total_user = $totalUser;
            $data->total = $total;
            $data->user = $user;
            $data->doctor = $doctor;
            $data->hospital = $hospital;

            return $this->responseOK(200, $data, 'Thống kê người dùng thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function statisticalArticle($request, $role)
    {
        if ($request->start_date) {
            $startDate = Carbon::parse($request->start_date);
        } else {
            $startDate = Article::orderBy('created_at', 'asc')->value('created_at');
        }

        if ($request->end_date) {
            $endDate = Carbon::parse($request->end_date);
        } else {
            $endDate = Carbon::now();
        }

        $articleData = Article::leftJoin('users', 'articles.id_user', '=', 'users.id')
            ->whereDate('articles.created_at', '>=', $startDate)
            ->whereDate('articles.created_at', '<=', $endDate)
            ->when(!empty($role), function ($query) use ($role) {
                if ($role === 'admin') {
                    $query->where('articles.id_user', null);
                } else {
                    $query->where('users.role', $role);
                }
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get([
                DB::raw('DATE(articles.created_at) as date'),
                DB::raw('COUNT(articles.id) as article_count'),
            ]);
        $result = $articleData->pluck('article_count', 'date')->toArray();

        $dates = [];
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->toDateString();
        }

        foreach ($dates as $date) {
            if (array_key_exists($date, $result)) {
                $result[$date] = $result[$date];
            } else {
                $result[$date] = 0;
            }
        }
        ksort($result);

        return $result;
    }

    public function article(RequestStatistical $request)
    {
        try {
            $data = (object) [];

            $totalArticle = [
                'admin' => Article::leftJoin('users', 'articles.id_user', '=', 'users.id')->where('articles.id_user', null)->count(),
                'doctor' => Article::leftJoin('users', 'articles.id_user', '=', 'users.id')->where('users.role', 'doctor')->count(),
                'hospital' => Article::leftJoin('users', 'articles.id_user', '=', 'users.id')->where('users.role', 'hospital')->count(),
            ];
            $data->total_article = $totalArticle;

            $total = $this->statisticalArticle($request, '');
            $admin = $this->statisticalArticle($request, 'admin');
            $doctor = $this->statisticalArticle($request, 'doctor');
            $hospital = $this->statisticalArticle($request, 'hospital');
            $data->total = $total;
            $data->admin = $admin;
            $data->doctor = $doctor;
            $data->hospital = $hospital;

            return $this->responseOK(200, $data, 'Thống kê bài viết thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function top(RequestStatistical $request)
    {
        try {
            $user = Auth::user();
            $data = (object) [];
            $top = $request->top ?? 5;
            $type = $request->type;

            // articles
            if ($type == 'article') {
                $filter = (object) [
                    'search' => '',
                    'orderBy' => 'articles.search_number',
                    'orderDirection' => 'DESC',
                    'is_accept' => 'both',
                    'is_show' => 'both',
                ];
                if ($user->role == 'hospital') {
                    $filter->id_hospital = $user->id;
                    $doctors = InforDoctorRepository::getInforDoctor(['id_hospital' => $user->id])->get();
                    $idDoctorHospitals = [];
                    $idDoctorHospitals[] = $user->id;
                    foreach ($doctors as $doctor) {
                        $idDoctorHospitals[] = $doctor->id_doctor;
                    }
                    $filter->id_doctor_hospital = $idDoctorHospitals;
                }
                $articles = ArticleRepository::searchAll($filter)->paginate($top);
                $data->top_articles = $articles;
            }

            // categories
            if ($type == 'category') {
                $filter = (object) [
                    'search' => '',
                    'orderBy' => 'categories.search_number',
                    'orderDirection' => 'DESC',
                ];
                $categories = CategoryRepository::searchCategory($filter)->paginate($top);
                $data->top_categories = $categories;
            }

            // departments
            if ($type == 'department') {
                $filter = (object) [
                    'search' => '',
                    'orderBy' => 'departments.search_number',
                    'orderDirection' => 'DESC',
                    'id_departments' => $id_departments ?? [0],
                ];
                $departments = DepartmentRepository::searchDepartment($filter)->paginate($top);
                $data->top_departments = $departments;
            }

            // doctor
            if ($type == 'doctor') {
                $filter = (object) [
                    'search' => '',
                    'role' => 'doctor',
                    'orderBy' => 'infor_doctors.search_number',
                    'is_accept' => 'both',
                    'is_confirm' => 'both',
                    'name_department' => '',
                    'orderDirection' => 'DESC',
                ];
                if ($user->role == 'hospital') {
                    $filter->id_hospital = $user->id;
                }
                $doctors = UserRepository::doctorOfHospital($filter)->paginate($top);
                $data->top_doctors = $doctors;
            }

            // hospitals
            if ($type == 'hospital') {
                $filter = (object) [
                    'search' => '',
                    'is_accept' => 'both',
                    'orderBy' => 'infor_hospitals.search_number',
                    'orderDirection' => 'DESC',
                ];
                $hospitals = InforHospitalRepository::searchHospital($filter)->paginate($top);
                foreach ($hospitals as $hospital) {
                    $hospital->infrastructure = json_decode($hospital->infrastructure);
                    $hospital->location = json_decode($hospital->location);
                }
                $data->top_hospitals = $hospitals;
            }

            return $this->responseOK(200, $data, 'Thống kê những bài viết , danh mục , chuyên khoa , bệnh viện nổi bật thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function overViewArticle($is_accept, $is_show, $request)
    {
        if ($request->start_date) {
            $startDate = Carbon::parse($request->start_date);
        } else {
            $startDate = Article::orderBy('created_at', 'asc')->value('created_at');
        }

        if ($request->end_date) {
            $endDate = Carbon::parse($request->end_date);
        } else {
            $endDate = Carbon::now();
        }

        $user = Auth::user();
        // articles
        $filter = (object) [
            'search' => '',
            'orderBy' => 'articles.search_number',
            'orderDirection' => 'DESC',
            'is_accept' => $is_accept,
            'is_show' => $is_show,
        ];
        if ($user->role == 'hospital') {
            $filter->id_hospital = $user->id;
            $doctors = InforDoctorRepository::getInforDoctor(['id_hospital' => $user->id])->get();
            $idDoctorHospitals = [];
            $idDoctorHospitals[] = $user->id;
            foreach ($doctors as $doctor) {
                $idDoctorHospitals[] = $doctor->id_doctor;
            }
            $filter->id_doctor_hospital = $idDoctorHospitals;
        }

        return ArticleRepository::searchAll($filter) // hay
            ->whereDate('articles.created_at', '>=', $startDate)
            ->whereDate('articles.created_at', '<=', $endDate)->count();

        // ->when(!empty($year), function ($query) use ($year) {
        // $query->whereYear('articles.created_at', $year);
        // })
    }

    public function overViewWorkSchedule($is_service, $status, $is_confirm, $request)
    {
        if ($request->start_date) {
            $startDate = Carbon::parse($request->start_date);
        } else {
            $startDate = WorkSchedule::orderBy('created_at', 'asc')->value('created_at');
        }

        if ($request->end_date) {
            $endDate = Carbon::parse($request->end_date);
        } else {
            $endDate = Carbon::now();
        }

        $user = Auth::user();
        // workSchedule
        $filter = (object) [
            'search' => '',
            'department_name' => '',
            'hospital_id' => $user->id,
            'doctor_id' => null,
            'is_service' => $is_service ?? '',
            'start_date' => '',
            'end_date' => '',
            'orderBy' => 'work_schedules.id',
            'orderDirection' => 'DESC',
            'status' => $status ?? '',
            'role' => 'hospital',
            'is_confirm' => $is_confirm ?? 'both',

            'overview_start_date' => $startDate,
            'overview_end_date' => $endDate,
        ];

        return WorkScheduleRepository::searchWorkScheduleStatistical($filter)->count();
    }

    public function overViewUser($request)
    {
        if ($request->start_date) {
            $startDate = Carbon::parse($request->start_date);
        } else {
            $startDate = User::orderBy('created_at', 'asc')->value('created_at');
        }

        if ($request->end_date) {
            $endDate = Carbon::parse($request->end_date);
        } else {
            $endDate = Carbon::now();
        }

        $query = User::whereDate('users.created_at', '>=', $startDate)
            ->whereDate('users.created_at', '<=', $endDate);

        $user = (object) [
            'all' => $query->count(),
            'accept' => $query->where('is_accept', 1)->count(),
        ];

        return $user;
    }

    public function overview(Request $request)
    {
        try {
            $user = Auth::user();
            $data = (object) [];

            $article = (object) [
                'all' => $this->overViewArticle('both', 'both', $request),
                'accept' => $this->overViewArticle('1', 'both', $request),
                'show' => $this->overViewArticle('both', '1', $request),
            ];
            $data->article = $article;

            if ($user->role == 'hospital') {
                $work_schedule = (object) [
                    'all' => $this->overViewWorkSchedule('', '', 'both', $request),
                    'confirm' => $this->overViewWorkSchedule('', '', 1, $request),
                    'confirm_and_complete' => $this->overViewWorkSchedule('', 'complete', 1, $request),
                    'service' => $this->overViewWorkSchedule('service', '', 'both', $request),
                    'advice' => $this->overViewWorkSchedule('advise', '', 'both', $request),
                ];
                $data->work_schedule = $work_schedule;
            }

            if ($user->role != 'hospital') {
                $data->user = $this->overViewUser($request);
            }

            return $this->responseOK(200, $data, 'Thống kê tổng quan thành công ! ');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function advise(RequestStatistical $request)
    {
        try {
            $data = (object) [];

            if ($request->start_date) {
                $startDate = Carbon::parse($request->start_date);
            } else {
                $startDate = WorkSchedule::orderBy('created_at', 'asc')->value('created_at');
            }

            if ($request->end_date) {
                $endDate = Carbon::parse($request->end_date);
            } else {
                $endDate = Carbon::now();
            }

            $hospital = Auth::user();

            $revenueData = WorkSchedule::join('infor_doctors', 'infor_doctors.id_doctor', '=', 'work_schedules.id_doctor')
                ->whereDate('work_schedules.created_at', '>=', $startDate)
                ->whereDate('work_schedules.created_at', '<=', $endDate)
                ->where('infor_doctors.id_hospital', $hospital->id)->whereNull('work_schedules.id_service')
                ->where('work_schedules.is_confirm', 1)
                ->groupBy('date')
                ->orderBy('date')
                ->get([
                    DB::raw('DATE(work_schedules.created_at) as date'),
                    DB::raw('SUM(work_schedules.price) as total_price'),
                ]);
            $result = $revenueData->pluck('total_price', 'date')->toArray();

            $dates = [];
            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                $dates[] = $date->toDateString();
            }

            foreach ($dates as $date) {
                if (array_key_exists($date, $result)) {
                    $result[$date] = $result[$date];
                } else {
                    $result[$date] = 0;
                }
            }
            ksort($result);

            $data->advise = $result;

            return $this->responseOK(200, $data, 'Thống kê doanh thu tư vấn của bệnh viện thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function service(RequestStatistical $request)
    {
        try {
            $data = (object) [];

            if ($request->start_date) {
                $startDate = Carbon::parse($request->start_date);
            } else {
                $startDate = WorkSchedule::orderBy('created_at', 'asc')->value('created_at');
            }

            if ($request->end_date) {
                $endDate = Carbon::parse($request->end_date);
            } else {
                $endDate = Carbon::now();
            }

            $hospital = Auth::user();

            $revenueData = WorkSchedule::join('hospital_services', 'hospital_services.id', '=', 'work_schedules.id_service')
                ->join('hospital_departments', 'hospital_departments.id', '=', 'hospital_services.id_hospital_department')
                ->whereDate('work_schedules.created_at', '>=', $startDate)
                ->whereDate('work_schedules.created_at', '<=', $endDate)
                ->where('hospital_departments.id_hospital', $hospital->id)->whereNotNull('id_service')
                ->where('work_schedules.is_confirm', 1)
                ->groupBy('date')
                ->orderBy('date')
                ->get([
                    DB::raw('DATE(work_schedules.created_at) as date'),
                    DB::raw('SUM(work_schedules.price) as total_price'),
                ]);
            $result = $revenueData->pluck('total_price', 'date')->toArray();

            $dates = [];
            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                $dates[] = $date->toDateString();
            }

            foreach ($dates as $date) {
                if (array_key_exists($date, $result)) {
                    $result[$date] = $result[$date];
                } else {
                    $result[$date] = 0;
                }
            }
            ksort($result);

            $data->advise = $result;

            return $this->responseOK(200, $data, 'Thống kê doanh thu dịch vụ của bệnh viện thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function serviceTable(RequestStatistical $request)
    {
        try {
            if ($request->start_date) {
                $startDate = Carbon::parse($request->start_date);
            } else {
                $startDate = WorkSchedule::orderBy('created_at', 'asc')->value('created_at');
            }

            if ($request->end_date) {
                $endDate = Carbon::parse($request->end_date);
            } else {
                $endDate = Carbon::now();
            }

            $hospital = Auth::user();

            $orderBy = 'total_revenue';
            if ($request->typesort == 'rating') {
                $orderBy = 'average_rating';
            }

            $orderDirection = 'DESC';
            if ($request->sortlatest != 'true') {
                $orderDirection = 'ASC';
            }

            $hospitalService = HospitalService::leftJoin('work_schedules', function ($join) use ($startDate, $endDate) {
                $join->on('work_schedules.id_service', '=', 'hospital_services.id')
                    ->whereNotNull('work_schedules.id_service')
                    ->where('work_schedules.is_confirm', 1)
                    ->whereDate('work_schedules.created_at', '>=', $startDate)
                    ->whereDate('work_schedules.created_at', '<=', $endDate);
                // đảm bảo chỉ thực hiện để tính total_revenue đối với các service có work_schedule
                // còn service nào không có thì vẫn hiện ra total_revenue là 0
            })
                ->leftJoin('ratings', 'ratings.id_work_schedule', '=', 'work_schedules.id')
                ->leftJoin('hospital_departments', 'hospital_departments.id', '=', 'hospital_services.id_hospital_department')
                ->where('hospital_departments.id_hospital', $hospital->id)
                ->leftJoin('departments', 'departments.id', '=', 'hospital_departments.id_department')
                ->selectRaw('
                COALESCE(SUM(work_schedules.price), 0) as total_revenue,
                COALESCE(COUNT(work_schedules.id), 0) as count_work_schedule,
                COALESCE((COALESCE(SUM(ratings.number_rating), 0) / COUNT(ratings.id)), 0) as average_rating,
                COALESCE(COUNT(ratings.id), 0) as count_rating,

                hospital_services.id as service_id, 
                hospital_services.name as service_name , 
                hospital_services.time_advise as service_time_advise,
                hospital_services.price as service_price , 
                hospital_services.infor as service_infor ,
                hospital_services.thumbnail_service as service_thumbnail ,
                hospital_services.search_number_service as service_search_number ,

                hospital_departments.id_hospital as id_hospital,
                hospital_departments.id_department as department_id,
                hospital_departments.price as department_price,
                hospital_departments.time_advise as department_time_advise ,

                departments.name as department_name ,
                departments.description as department_description,
                departments.thumbnail as department_thumbnail
            ')
                ->groupBy(
                    'hospital_services.id',
                )
                ->orderBy($orderBy, $orderDirection);

            if (!(empty($request->paginate))) {
                $hospitalService = $hospitalService->paginate($request->paginate);
            } else {
                $hospitalService = $hospitalService->get();
            }

            return $this->responseOK(200, $hospitalService, 'Thống kê doanh thu dịch vụ của bệnh viện thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function adviseTable(RequestStatistical $request)
    {
        try {
            if ($request->start_date) {
                $startDate = Carbon::parse($request->start_date);
            } else {
                $startDate = WorkSchedule::orderBy('created_at', 'asc')->value('created_at');
            }

            if ($request->end_date) {
                $endDate = Carbon::parse($request->end_date);
            } else {
                $endDate = Carbon::now();
            }

            $hospital = Auth::user();

            $orderBy = 'total_revenue';
            if ($request->typesort == 'rating') {
                $orderBy = 'average_rating';
            }

            $orderDirection = 'DESC';
            if ($request->sortlatest != 'true') {
                $orderDirection = 'ASC';
            }

            $doctors = User::join('infor_doctors', 'users.id', '=', 'infor_doctors.id_doctor')
                ->join('departments', 'departments.id', '=', 'infor_doctors.id_department')
                ->join('hospital_departments', function ($join) {
                    $join->on('hospital_departments.id_department', '=', 'infor_doctors.id_department')
                        ->on('hospital_departments.id_hospital', '=', 'infor_doctors.id_hospital');
                })
                ->leftJoin('work_schedules', function ($join) use ($startDate, $endDate) {
                    $join->on('work_schedules.id_doctor', '=', 'infor_doctors.id_doctor')
                        ->whereNull('work_schedules.id_service')
                        ->where('work_schedules.is_confirm', 1)
                        ->whereDate('work_schedules.created_at', '>=', $startDate)
                        ->whereDate('work_schedules.created_at', '<=', $endDate);
                })
                ->leftJoin('ratings', 'ratings.id_work_schedule', '=', 'work_schedules.id')
                ->where('hospital_departments.id_hospital', $hospital->id)
                ->selectRaw('
                COALESCE(SUM(work_schedules.price), 0) as total_revenue,
                COALESCE(COUNT(work_schedules.id), 0) as count_work_schedule,
                COALESCE((COALESCE(SUM(ratings.number_rating), 0) / COUNT(ratings.id)), 0) as average_rating,
                COALESCE(COUNT(ratings.id), 0) as count_rating,

                users.id AS doctor_id, 
                users.name AS doctor_name, 
                users.address AS doctor_address, 
                users.avatar AS doctor_avatar, 
                users.email AS doctor_email, 
                users.phone AS doctor_phone, 

                hospital_departments.id_hospital as id_hospital,
                hospital_departments.id_department as department_id,
                hospital_departments.price as department_price,
                hospital_departments.time_advise as department_time_advise ,

                departments.name as department_name ,
                departments.description as department_description,
                departments.thumbnail as department_thumbnail
            ')
                ->groupBy(
                    'users.id', 'hospital_departments.id', 'departments.id'
                )
                ->orderBy($orderBy, $orderDirection);

            if (!(empty($request->paginate))) {
                $doctors = $doctors->paginate($request->paginate);
            } else {
                $doctors = $doctors->get();
            }

            return $this->responseOK(200, $doctors, 'Thống kê doanh thu tư vấn của bệnh viện thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }
}
