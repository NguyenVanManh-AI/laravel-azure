<?php

namespace App\Repositories;

use App\Models\Rating;

class RatingRepository extends BaseRepository implements RatingInterface
{
    public function getModel()
    {
        return Rating::class;
    }

    public static function findById($id)
    {
        return (new self)->model->find($id);
    }

    public static function searchRating($filter)
    {
        // infor_doctors.* , infor_hospitals.* , infor_users.* ,
        $filter = (object) $filter;
        $query = (new self)->model->selectRaw('
                users_doctor.id ,  users_hospital.id , users_user.id , departments.id ,
                hd_service.id , ih_service.id , uh_service.id , hd_department.id ,
                work_schedules.id ,
    
                ratings.id AS rating_id,
                ratings.number_rating AS rating_number_rating,
                ratings.detail_rating AS rating_detail_rating,
                ratings.created_at AS rating_created_at,
                ratings.updated_at AS rating_updated_at,

                hospital_services.id AS service_id, 
                hospital_services.name AS service_name , 
                hospital_services.time_advise AS service_time_advise,
                hospital_services.price as service_price , 
                hospital_services.infor as service_infor ,
                hospital_services.thumbnail_service as thumbnail_service ,
                hospital_services.search_number_service as search_number_service ,
    
                work_schedules.id AS work_schedule_id ,
                work_schedules.price AS work_schedule_price , work_schedules.time AS work_schedule_time , 
                work_schedules.content AS work_schedule_content , 
                work_schedules.name_patient AS name_patient , 
                work_schedules.date_of_birth_patient AS date_of_birth_patient , 
                work_schedules.gender_patient AS gender_patient , 
                work_schedules.email_patient AS email_patient , 
                work_schedules.phone_patient AS phone_patient , 
                work_schedules.address_patient AS address_patient , 
                work_schedules.health_condition AS health_condition , 
                work_schedules.is_confirm AS work_schedule_is_confirm , 
                work_schedules.created_at AS work_schedule_created_at ,
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
                COALESCE(infor_hospitals.cover_hospital, ih_service.cover_hospital) AS cover_hospital ,
    
                COALESCE(departments.id, hd_department.id) AS department_id ,
                COALESCE(departments.name, hd_department.name) AS department_name ,
                COALESCE(departments.description, hd_department.description) AS department_description,
                COALESCE(departments.thumbnail, hd_department.thumbnail) AS department_thumbnail,
                COALESCE(hospital_departments.price, hd_service.price) AS department_price,
                COALESCE(hospital_departments.time_advise, hd_service.time_advise) AS department_time_advise
    
            ');

        $query->join('work_schedules', 'ratings.id_work_schedule', '=', 'work_schedules.id');

        // ở đây là rating , khác với work_schedules , rating thì không ẩn danh được , phải có account thì mới
        // đánh giá được , nhưng mà để cho chắc thì cứ leftJoin thay vì join cũng được
        // ở đây chắc chắn nó có id_user trong mỗi rating
        $query->leftJoin('infor_users', 'infor_users.id_user', '=', 'ratings.id_user')
            ->leftJoin('users AS users_user', 'users_user.id', '=', 'infor_users.id_user');

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

        $query->when(isset($filter->is_confirm), function ($query) use ($filter) {
            $query->where(function ($query) use ($filter) {
                if ($filter->is_confirm === 'both') {
                } else {
                    $query->where('work_schedules.is_confirm', $filter->is_confirm);
                }
            });
        });

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
                            $query->where('users_user.name', 'LIKE', '%' . $filter->search . '%')
                                ->orWhere('users_user.address', 'LIKE', '%' . $filter->search . '%')
                                ->orWhere('users_user.email', 'LIKE', '%' . $filter->search . '%')
                                ->orWhere('users_user.phone', 'LIKE', '%' . $filter->search . '%')
                                ->orWhere('hospital_services.name', 'LIKE', '%' . $filter->search . '%')
                                ->orWhere('work_schedules.content', 'LIKE', '%' . $filter->search . '%')

                                ->orWhere('work_schedules.name_patient', 'LIKE', '%' . $filter->search . '%')
                                ->orWhere('work_schedules.email_patient', 'LIKE', '%' . $filter->search . '%')
                                ->orWhere('work_schedules.phone_patient', 'LIKE', '%' . $filter->search . '%')
                                ->orWhere('work_schedules.address_patient', 'LIKE', '%' . $filter->search . '%');
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

        $query->when(!empty($filter->service_name), function ($query) use ($filter) {
            $query->where(function ($query) use ($filter) { // bỏ query vào hàm rất quan trọng , không có nó sẽ khác
                $query->where('hospital_services.name', $filter->service_name);
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
        $query->when(isset($filter->doctor_id), function ($query) use ($filter) {
            $query->where(function ($query) use ($filter) {
                $query->where('work_schedules.id_doctor', $filter->doctor_id);
            });
        });
        $query->when(isset($filter->user_id), function ($query) use ($filter) {
            $query->where(function ($query) use ($filter) {
                $query->where('work_schedules.id_user', $filter->user_id);
            });
        });
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
                                    ->orWhere('work_schedules.content', 'LIKE', '%' . $filter->search . '%')

                                    ->orWhere('work_schedules.name_patient', 'LIKE', '%' . $filter->search . '%')
                                    ->orWhere('work_schedules.email_patient', 'LIKE', '%' . $filter->search . '%')
                                    ->orWhere('work_schedules.phone_patient', 'LIKE', '%' . $filter->search . '%')
                                    ->orWhere('work_schedules.address_patient', 'LIKE', '%' . $filter->search . '%');
                            }
                        });
                    });
                });
            });
        });
        $query->when(isset($filter->is_confirm), function ($query) use ($filter) {
            $query->where(function ($query) use ($filter) {
                if ($filter->is_confirm === 'both') {
                } else {
                    $query->where('work_schedules.is_confirm', $filter->is_confirm);
                }
            });
        });

        return $query;
    }

    public static function getRating($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model->selectRaw(
            'ratings.id as id_rating , work_schedules.id , users.id as id_user , ratings.*, 
                users.name as name_user, users.avatar as avatar_user 
            '
        )
            ->join('work_schedules', 'work_schedules.id', '=', 'ratings.id_work_schedule')
            ->join('users', 'users.id', '=', 'ratings.id_user')
            // workschedule có loại id_service null (tư vấn)

            ->when(!empty($filter->list_id_work_schedule), function ($query) use ($filter) {
                $query->whereIn('ratings.id_work_schedule', $filter->list_id_work_schedule);
            })
            ->when(!empty($filter->number_rating), function ($query) use ($filter) {
                $query->where('ratings.number_rating', $filter->number_rating);
            })
            ->when(isset($filter->orderBy), function ($query) use ($filter) {
                $query->orderBy($filter->orderBy, $filter->orderDirection);
            });

        return $data;
    }
}
