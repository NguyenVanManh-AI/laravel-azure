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
            ->when(!empty($filter->id), function ($q) use ($filter) {
                $q->where('id', $filter->id);
            })
            ->when(!empty($filter->id_doctor), function ($q) use ($filter) {
                $q->where('id_doctor', $filter->id_doctor);
            })
            ->when(!empty($filter->time), function ($q) use ($filter) {
                $q->whereJsonContains('time', [
                    'date' => $filter->time['date'],
                    'interval' => $filter->time['interval'],
                ]);
            })
            ->when(isset($filter->id_service), function ($query) use ($filter) {
                $query->where('id_service', $filter->id_service === 'advise' ? null : $filter->id_service);
            });

        return $data;
    }

    public static function searchWorkSchedule($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model->selectRaw(
            'work_schedules.*, hospital_services.*, infor_doctors.*, 
            infor_users.*, infor_hospitals.*, departments.*, users_doctor.*, users_user.*, hospital_departments.*,

            users_doctor.id AS doctor_id, users_user.id AS user_id, users_hospital.id AS hospital_id,
            users_doctor.name AS doctor_name, users_user.name AS user_name, users_hospital.name AS hospital_name,
            users_doctor.address AS doctor_address, users_user.address AS user_address, users_hospital.address AS hospital_address,
            users_doctor.avatar AS doctor_avatar, users_user.avatar AS user_avatar, users_hospital.avatar AS hospital_avatar,
            users_doctor.email AS doctor_email, users_user.email AS user_email, users_hospital.email AS hospital_email,
            users_doctor.phone AS doctor_phone, users_user.phone AS user_phone, users_hospital.phone AS hospital_phone,

            departments.name as department_name, departments.description as department_description, departments.thumbnail as department_thumbnail,
            hospital_services.name as hospital_service_name, hospital_services.time_advise as hospital_service_time_advise,
            hospital_services.price as hospital_service_price , hospital_services.infor as hospital_service_infor,
            hospital_departments.price as hospital_department_price , hospital_departments.time_advise as hospital_department_time_advise , 
            work_schedules.id as work_schedule_id , work_schedules.price as work_schedule_price,
            infor_users.date_of_birth as infor_user_date_of_birth
            
            ')
            ->join('infor_doctors', 'infor_doctors.id_doctor', '=', 'work_schedules.id_doctor')
            ->join('hospital_departments', function ($join) { // join với 2 điều kiện
                $join->on('hospital_departments.id_department', '=', 'infor_doctors.id_department')
                    ->on('hospital_departments.id_hospital', '=', 'infor_doctors.id_hospital');
            })
            ->join('infor_hospitals', 'infor_hospitals.id_hospital', '=', 'infor_doctors.id_hospital')
            ->join('users as users_hospital', 'users_hospital.id', '=', 'infor_hospitals.id_hospital')
            ->join('departments', 'departments.id', '=', 'infor_doctors.id_department')
            ->join('users as users_doctor', 'users_doctor.id', '=', 'infor_doctors.id_doctor')
            ->join('infor_users', 'infor_users.id_user', '=', 'work_schedules.id_user')
            ->join('users as users_user', 'users_user.id', '=', 'infor_users.id_user')
            ->leftJoin('hospital_services', 'work_schedules.id_service', '=', 'hospital_services.id')

            ->when(!empty($filter->search), function ($q) use ($filter) {
                if ($filter->role == 'user') {
                    $q->where(function ($query) use ($filter) {
                        $query->where('users_doctor.name', 'LIKE', '%' . $filter->search . '%')
                            ->orWhere('users_hospital.name', 'LIKE', '%' . $filter->search . '%')
                            ->orWhere('users_doctor.phone', 'LIKE', '%' . $filter->search . '%')
                            ->orWhere('users_hospital.phone', 'LIKE', '%' . $filter->search . '%')
                            ->orWhere('users_doctor.address', 'LIKE', '%' . $filter->search . '%')
                            ->orWhere('users_hospital.address', 'LIKE', '%' . $filter->search . '%')
                            ->orWhere('departments.name', 'LIKE', '%' . $filter->search . '%')
                            ->orWhere('departments.description', 'LIKE', '%' . $filter->search . '%')
                            ->orWhere('hospital_services.name', 'LIKE', '%' . $filter->search . '%');
                    });
                } else {
                    $q->where(function ($query) use ($filter) {
                        $query->where('users_user.name', 'LIKE', '%' . $filter->search . '%')
                            ->orWhere('users_user.address', 'LIKE', '%' . $filter->search . '%')
                            ->orWhere('users_user.email', 'LIKE', '%' . $filter->search . '%')
                            ->orWhere('users_user.phone', 'LIKE', '%' . $filter->search . '%')
                            ->orWhere('hospital_services.name', 'LIKE', '%' . $filter->search . '%');
                    });
                }
            })
            ->when(!empty($filter->user_id), function ($query) use ($filter) { // user
                $query->where('users_user.id', '=', $filter->user_id);
            })
            ->when(!empty($filter->hospital_id), function ($query) use ($filter) { // hospital delete Many
                $query->where('users_hospital.id', $filter->hospital_id);
            })
            ->when(!empty($filter->list_id), function ($query) use ($filter) {
                $query->whereIn('work_schedules.id', $filter->list_id);
            })
            ->when(!empty($filter->doctors_id), function ($query) use ($filter) {  // doctor hoặc hospital
                $query->whereIn('users_doctor.id', $filter->doctors_id);
            })

            ->when(!empty($filter->department_name), function ($query) use ($filter) {
                $query->where('departments.name', $filter->department_name);
            })
            ->when(!empty($filter->start_date), function ($query) use ($filter) {
                $query->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(work_schedules.time, "$.date")) >= ?', [$filter->start_date]);
            })
            ->when(!empty($filter->end_date), function ($query) use ($filter) {
                $query->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(work_schedules.time, "$.date")) <= ?', [$filter->end_date]);
            })
            ->when(isset($filter->is_service), function ($query) use ($filter) {
                return $query->where(function ($query) use ($filter) {
                    if ($filter->is_service === 'advise') {
                        $query->whereNull('work_schedules.id_service');
                    } elseif ($filter->is_service === 'service') {
                        $query->whereNotNull('work_schedules.id_service');
                    }
                });
            })

            ->when(!empty($filter->orderBy), function ($query) use ($filter) {
                if ($filter->orderBy === 'time->date') {
                    $query->orderByRaw("time->'$.date' $filter->orderDirection, JSON_UNQUOTE(JSON_EXTRACT(time, '$.interval[0]')) $filter->orderDirection");
                } else {
                    $query->orderBy($filter->orderBy, $filter->orderDirection);
                }
            })

            // detail
            ->when(!empty($filter->work_schedule_id), function ($query) use ($filter) {
                $query->where('work_schedules.id', $filter->work_schedule_id);
            });

        return $data;
    }
}
