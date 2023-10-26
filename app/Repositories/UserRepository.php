<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserRepository extends BaseRepository implements UserInterface
{
    public function getModel()
    {
        return User::class;
    }

    public static function getUser()
    {
        return (new self)->model;
    }

    public static function findUserByEmail($email)
    {
        return (new self)->model->where('email', $email)->first();
    }

    public static function findUserById($id)
    {
        return (new self)->model->find($id);
    }

    public static function updateUser($id, $data)
    {
        DB::beginTransaction();
        try {
            $user = (new self)->model->find($id);
            $user->update($data);
            DB::commit();

            return $user;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function findUserByTokenVerifyEmail($token)
    {
        return $this->model->where('token_verify_email', $token)->first();
    }

    public static function findUser($filter)
    {
        $filter = (object) $filter;
        $user = (new self)->model
            ->when(!empty($filter->id), function ($q) use ($filter) {
                $q->where('id', $filter->id);
            })
            ->when(!empty($filter->email), function ($q) use ($filter) {
                $q->where('email', $filter->email);
            })
            ->when(!empty($filter->role), function ($q) use ($filter) {
                $q->where('role', $filter->role);
            });

        return $user;
    }

    public static function createUser($data)
    {
        DB::beginTransaction();
        try {
            $newUser = (new self)->model->create($data);
            DB::commit();

            return $newUser;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public static function searchUser($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model
            ->when(!empty($filter->search), function ($q) use ($filter) {
                $q->where(function ($query) use ($filter) {
                    $query->where('name', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('address', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('email', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('phone', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('username', 'LIKE', '%' . $filter->search . '%');
                });
            })
            ->when(!empty($filter->role), function ($query) use ($filter) {
                return $query->where('role', 'LIKE', '%' . $filter->role . '%');
            })
            ->when(isset($filter->is_accept), function ($query) use ($filter) {
                if ($filter->is_accept === 'both') {
                } else {
                    $query->where('is_accept', $filter->is_accept);
                }
            })
            ->when(!empty($filter->orderBy), function ($query) use ($filter) {
                $query->orderBy($filter->orderBy, $filter->orderDirection);
            });

        return $data;
    }

    // leftjoin để nếu bác sĩ thuộc bệnh viện này và bác sĩ thuộc chuyên khoa A mà bệnh viện
    // này không có chuyên khoa A vẫn lấy ra được
    // vẫn lấy ra đầy đủ thông tin doctor còn hospital_departments thì null . rightjoin thì ngược lại
    public static function doctorOfHospital($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model->selectRaw('users.*, infor_doctors.*, departments.*,hospital_departments.*,
        users.name as name_doctor, departments.name as name_department,
        infor_doctors.search_number as search_number_doctor, departments.search_number as search_number_department,
        users_hospital.name as name_hospital
        
        ')

            ->join('infor_doctors', 'users.id', '=', 'infor_doctors.id_doctor')
            ->join('departments', 'departments.id', '=', 'infor_doctors.id_department')
            ->join('hospital_departments', function ($join) { // join với 2 điều kiện
                $join->on('hospital_departments.id_department', '=', 'infor_doctors.id_department')
                    ->on('hospital_departments.id_hospital', '=', 'infor_doctors.id_hospital');
            })
            ->join('users as users_hospital', 'users_hospital.id', '=', 'infor_doctors.id_hospital')
            ->when(!empty($filter->search), function ($q) use ($filter) {
                $q->where(function ($query) use ($filter) {
                    $query->where('users.name', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('users.address', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('users.username', 'LIKE', '%' . $filter->search . '%');
                });
            })
            ->when(!empty($filter->name_department), function ($query) use ($filter) {
                return $query->where('departments.name', $filter->name_department);
            })
            ->when(!empty($filter->role), function ($query) use ($filter) {
                return $query->where('users.role', 'LIKE', '%' . $filter->role . '%');
            })
            ->when(isset($filter->is_accept), function ($query) use ($filter) {
                if ($filter->is_accept === 'both') {
                } else {
                    $query->where('users.is_accept', $filter->is_accept);
                }
            })
            ->when(isset($filter->is_confirm), function ($query) use ($filter) {
                if ($filter->is_confirm === 'both') {
                } else {
                    $query->where('infor_doctors.is_confirm', $filter->is_confirm);
                }
            })
            ->when(!empty($filter->id_hospital), function ($query) use ($filter) {
                return $query->where('infor_doctors.id_hospital', $filter->id_hospital);
            })
            ->when(!empty($filter->id_doctor), function ($query) use ($filter) {
                return $query->where('infor_doctors.id_doctor', $filter->id_doctor);
            })
            ->when(!empty($filter->id_department), function ($query) use ($filter) {
                return $query->where('infor_doctors.id_department', $filter->id_department);
            })
            ->when(!empty($filter->orderBy), function ($query) use ($filter) {
                $query->orderBy($filter->orderBy, $filter->orderDirection);
            });

        return $data;
    }
}
