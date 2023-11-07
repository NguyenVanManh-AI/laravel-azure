<?php

namespace App\Repositories;

use App\Models\WorkSchedule;
use Illuminate\Support\Facades\DB;
use Throwable;

class WorkScheduleRepository extends BaseRepository implements WorkScheduleInterface
{
    public function getModel()
    {
        return WorkSchedule::class;
    }

    public static function findById($id)
    {
        return (new self)->model->find($id);
    }

    public static function createWorkSchedule($data)
    {
        DB::beginTransaction();
        try {
            $newWorkSchedule = (new self)->model->create($data);
            DB::commit();

            return $newWorkSchedule;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public static function getWorkSchedule($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model
            ->when(!empty($filter->id), function ($query) use ($filter) {
                $query->where('id', $filter->id);
            })
            ->when(!empty($filter->id_doctor), function ($query) use ($filter) {
                $query->where('id_doctor', $filter->id_doctor);
            })
            ->when(!empty($filter->id_doctors), function ($query) use ($filter) {
                $query->whereIn('id_doctor', $filter->id_doctors);
            })
            // listSpecifyDoctor
            ->when(!empty($filter->time), function ($query) use ($filter) {
                $query->where('time->date', $filter->time->date)
                    ->where(function ($query) use ($filter) {
                        $query->where(function ($query) use ($filter) {
                            $query->where('time->interval[0]', '>=', $filter->time->interval[0])
                                ->where('time->interval[1]', '<=', $filter->time->interval[1]);
                        })
                            ->orWhere(function ($query) use ($filter) {
                                $query->where('time->interval[0]', '<=', $filter->time->interval[0])
                                    ->where('time->interval[1]', '>=', $filter->time->interval[1]);
                            })
                            ->orWhere(function ($query) use ($filter) {
                                $query->where('time->interval[0]', '>=', $filter->time->interval[0])
                                    ->where('time->interval[0]', '<', $filter->time->interval[1]);
                            })
                            ->orWhere(function ($query) use ($filter) {
                                $query->where('time->interval[1]', '>', $filter->time->interval[0])
                                    ->where('time->interval[1]', '<=', $filter->time->interval[1]);
                            });
                    });
            })
            ->when(isset($filter->id_service), function ($query) use ($filter) {
                $query->where('id_service', $filter->id_service === 'advise' ? null : $filter->id_service);
            });

        return $data;
    }

    public static function searchWorkSchedule($filter)
    {
        // infor_doctors.* , infor_hospitals.* , infor_users.* ,
        $filter = (object) $filter;
        $query = (new self)->model->selectRaw('
            users_doctor.id ,  users_hospital.id , users_user.id , departments.id ,
            hd_service.id , ih_service.id , uh_service.id , hd_department.id ,
            work_schedules.id ,

            hospital_services.id AS service_id, 
            hospital_services.name AS service_name , 
            hospital_services.time_advise AS service_time_advise,
            hospital_services.price as service_price , 
            hospital_services.infor as service_infor ,

            work_schedules.id AS work_schedule_id ,
            work_schedules.price AS work_schedule_price , work_schedules.time AS work_schedule_time , 
            work_schedules.content AS work_schedule_content , work_schedules.created_at AS work_schedule_created_at ,
            work_schedules.updated_at AS work_schedule_updated_at,
            
            users_user.id AS user_id, 
            users_user.name AS user_name, 
            users_user.address AS user_address, 
            users_user.avatar AS user_avatar, 
            users_user.email AS user_email, 
            users_user.phone AS user_phone,
            infor_users.date_of_birth as user_date_of_birth,

            users_doctor.id AS doctor_id, 
            users_doctor.name AS doctor_name, 
            users_doctor.address AS doctor_address, 
            users_doctor.avatar AS doctor_avatar, 
            users_doctor.email AS doctor_email, 
            users_doctor.phone AS doctor_phone, 

            COALESCE(users_hospital.id, uh_service.id) AS hospital_id ,
            COALESCE(users_hospital.name, uh_service.name) AS hospital_name ,
            COALESCE(users_hospital.address, uh_service.address) AS hospital_address ,
            COALESCE(users_hospital.avatar, uh_service.avatar) AS hospital_avatar ,
            COALESCE(users_hospital.email, uh_service.email) AS hospital_email ,
            COALESCE(users_hospital.phone, uh_service.phone) AS hospital_phone ,
            COALESCE(infor_hospitals.infrastructure, ih_service.infrastructure) AS hospital_infrastructure ,
            COALESCE(infor_hospitals.description, ih_service.description) AS hospital_description ,

            COALESCE(departments.id, hd_department.id) AS department_id ,
            COALESCE(departments.name, hd_department.name) AS department_name ,
            COALESCE(departments.description, hd_department.description) AS department_description,
            COALESCE(departments.thumbnail, hd_department.thumbnail) AS department_thumbnail,
            COALESCE(hospital_departments.price, hd_service.price) AS department_price,
            COALESCE(hospital_departments.time_advise, hd_service.time_advise) AS department_time_advise

        ');
        $query->join('infor_users', 'infor_users.id_user', '=', 'work_schedules.id_user')
            ->join('users AS users_user', 'users_user.id', '=', 'infor_users.id_user');

        $query->leftJoin('infor_doctors', 'infor_doctors.id_doctor', '=', 'work_schedules.id_doctor');
        $query->leftJoin('hospital_services', 'hospital_services.id', '=', 'work_schedules.id_service');

        // doctor
        $query->leftJoin('hospital_departments', function ($join) {
            $join->on('hospital_departments.id_department', '=', 'infor_doctors.id_department')
                ->on('hospital_departments.id_hospital', '=', 'infor_doctors.id_hospital');
        });
        $query->leftJoin('infor_hospitals', 'infor_hospitals.id_hospital', '=', 'infor_doctors.id_hospital');
        $query->leftJoin('users AS users_hospital', 'users_hospital.id', '=', 'infor_hospitals.id_hospital');
        $query->leftJoin('departments', 'departments.id', '=', 'infor_doctors.id_department');
        $query->leftJoin('users AS users_doctor', 'users_doctor.id', '=', 'infor_doctors.id_doctor');

        // service
        $query->leftJoin('hospital_departments AS hd_service', 'hospital_services.id_hospital_department', '=', 'hd_service.id');
        $query->leftJoin('infor_hospitals AS ih_service', 'ih_service.id_hospital', '=', 'hd_service.id_hospital');
        $query->leftJoin('users AS uh_service', 'uh_service.id', '=', 'ih_service.id_hospital');
        $query->leftJoin('departments AS hd_department', 'hd_department.id', '=', 'hd_service.id_department');

        $query->when(!empty($filter->list_id), function ($query) use ($filter) { // LƯU Ý : Bỏ cái này ở đầu nến không nó không hoạt động , chưa tìm ra lí do
            $query->whereIn('work_schedules.id', $filter->list_id);
        });
        $query->when(!empty($filter->user_id), function ($query) use ($filter) { // user
            $query->where('users_user.id', '=', $filter->user_id);
        });

        if ($filter->role == 'user') {
            $query->where(function ($query) use ($filter) { // bỏ vào function mới được
                // nếu không bỏ vào function thì user vừa search vừa is_service thì lại không được
                $query->where('users_doctor.name', 'LIKE', '%' . $filter->search . '%')
                    ->orWhere('users_hospital.name', 'LIKE', '%' . $filter->search . '%')
                    ->orWhere('users_doctor.phone', 'LIKE', '%' . $filter->search . '%')
                    ->orWhere('users_hospital.phone', 'LIKE', '%' . $filter->search . '%')
                    ->orWhere('users_doctor.address', 'LIKE', '%' . $filter->search . '%')
                    ->orWhere('users_hospital.address', 'LIKE', '%' . $filter->search . '%')
                    ->orWhere('departments.name', 'LIKE', '%' . $filter->search . '%')
                    ->orWhere('departments.description', 'LIKE', '%' . $filter->search . '%')
                    ->orWhere('hospital_services.name', 'LIKE', '%' . $filter->search . '%')
                    ->orWhere('work_schedules.content', 'LIKE', '%' . $filter->search . '%');
            });
        }

        $query->when(!empty($filter->hospital_id), function ($query) use ($filter) {
            $query->where(function ($query) use ($filter) {
                $query->where(function ($query) use ($filter) { // bỏ query vào hàm rất quan trọng , không có nó sẽ khác
                    $query->where('users_hospital.id', $filter->hospital_id)
                        ->orWhere('uh_service.id', $filter->hospital_id);
                });

                if (!empty($filter->doctor_id)) {
                    $query->where('users_doctor.id', $filter->doctor_id);
                }
                $query->when(!empty($filter->search), function ($query) use ($filter) {
                    $query->where(function ($query) use ($filter) {
                        $query->where(function ($query) use ($filter) {
                            if ($filter->role != 'user') {
                                $query->where('users_user.name', 'LIKE', '%' . $filter->search . '%')
                                    ->orWhere('users_user.address', 'LIKE', '%' . $filter->search . '%')
                                    ->orWhere('users_user.email', 'LIKE', '%' . $filter->search . '%')
                                    ->orWhere('users_user.phone', 'LIKE', '%' . $filter->search . '%')
                                    ->orWhere('hospital_services.name', 'LIKE', '%' . $filter->search . '%')
                                    ->orWhere('work_schedules.content', 'LIKE', '%' . $filter->search . '%');
                            }
                        });
                    });
                });
            });
        });

        $query->when(isset($filter->is_service), function ($query) use ($filter) {
            return $query->where(function ($query) use ($filter) {
                if ($filter->is_service === 'advise') {
                    $query->whereNull('work_schedules.id_service');
                } elseif ($filter->is_service === 'service') {
                    $query->whereNotNull('work_schedules.id_service');
                }
            });
        });

        $query->when(!empty($filter->department_name), function ($query) use ($filter) {
            $query->where(function ($query) use ($filter) { // bỏ query vào hàm rất quan trọng , không có nó sẽ khác
                $query->where('departments.name', $filter->department_name)
                    ->orWhere('hd_department.name', $filter->department_name);
            });
        });

        $query->when(!empty($filter->start_date), function ($query) use ($filter) {
            $query->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(work_schedules.time, "$.date")) >= ?', [$filter->start_date]);
        });
        $query->when(!empty($filter->end_date), function ($query) use ($filter) {
            $query->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(work_schedules.time, "$.date")) <= ?', [$filter->end_date]);
        });
        $query->when(!empty($filter->orderBy), function ($query) use ($filter) {
            if ($filter->orderBy === 'time->date') {
                $query->orderByRaw("time->'$.date' $filter->orderDirection, JSON_UNQUOTE(JSON_EXTRACT(time, '$.interval[0]')) $filter->orderDirection");
            } else {
                $query->orderBy($filter->orderBy, $filter->orderDirection);
            }
        });
        // detail
        $query->when(!empty($filter->work_schedule_id), function ($query) use ($filter) {
            $query->where('work_schedules.id', $filter->work_schedule_id);
        });

        // work schedule complete , upcoming
        // {"date": "2023-11-07", "interval": ["21:52", "22:30"]} // Dữ liệu trong db phải đúng như này (yyyy-mm-dd) (HH:ii)
        // mỗi bộ gồm 2 số
        $query->when(!empty($filter->status), function ($query) use ($filter) {
            $day = now()->toDateString();
            $hour = now()->format('H:i');
            if ($filter->status == 'complete') {
                $query->where('time->date', '<', $day)
                    ->orWhere(function ($query) use ($day, $hour) {
                        $query->where('time->date', '=', $day)->where('time->interval[0]', '<', $hour);
                    });
            } else {
                $query->where('time->date', '>', $day)
                    ->orWhere(function ($query) use ($day, $hour) {
                        $query->where('time->date', '=', $day)->where('time->interval[0]', '>=', $hour);
                    });
            }
        });

        return $query;
    }
}

// [QUAN TRỌNG]
// một vấn đề khác nữa là vì tôi không biết trường hợp nào cái nào null nên tôi lấy ra cả hai :             users_hospital.name AS hospital_name,
//             uh_service.name AS hospital_name, nhưng có cái thì nó null có cái thì không null , vì giá trị này luôn có nhưng tôi nghĩ do cách lấy ra của tôi sai
// ChatGPT
// Nếu bạn không biết giá trị nào có thể là null, và bạn muốn lấy cả hai trường users_hospital.name và uh_service.name dựa trên cả id_doctor và id_service, bạn có thể thực hiện điều này bằng cách sử dụng hàm COALESCE trong SQL. Hàm COALESCE cho phép bạn lấy giá trị không null đầu tiên trong danh sách các giá trị.

// Dưới đây là cách bạn có thể sử dụng COALESCE để lấy cả hai trường và bỏ qua giá trị null:

// php
// Copy code
// $query->selectRaw('
//     work_schedules.*,
//     hospital_services.*,
//     infor_doctors.*,
//     infor_hospitals.*,
//     users_hospital.*,
//     departments.*,
//     users_doctor.*,
//     infor_users.*,
//     users_user.*,
//     hd_service.*,
//     ih_service.*,
//     uh_service.*,
//     hd_department.*,
//     users_doctor.name AS doctor_name,
//     users_user.name AS user_name,
//     COALESCE(users_hospital.name, uh_service.name) AS hospital_name,
//     hospital_services.name AS name_service'
// );
// Ở đây, COALESCE(users_hospital.name, uh_service.name) sẽ lấy giá trị users_hospital.name nếu nó không null,
// nếu không nó sẽ lấy giá trị từ uh_service.name.
// Điều này sẽ đảm bảo bạn luôn lấy được giá trị từ trường nào có dữ liệu và bỏ qua trường có giá trị null.
