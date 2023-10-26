<?php

namespace App\Repositories;

use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Throwable;

class AdminRepository extends BaseRepository implements AdminInterface
{
    public function getModel()
    {
        return Admin::class;
    }

    public function getAdmin()
    {
        return $this->model;
    }

    public static function findAdminByEmail($email)
    {
        return (new self)->model->where('email', $email)->first();
    }

    public function findAdminById($id)
    {
        return $this->model->find($id);
    }

    public function findAdminByTokenVerifyEmail($token)
    {
        return $this->model->where('token_verify_email', $token)->first();
    }

    public function createAdmin($data)
    {
        DB::beginTransaction();
        try {
            $newAdmin = $this->model->create($data);
            DB::commit();

            return $newAdmin;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public static function updateAdmin($id, $data)
    {
        DB::beginTransaction();
        try {
            $admin = (new self)->model->find($id);
            $admin->update($data);
            DB::commit();

            return $admin;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public static function searchAdmin($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model
            ->when(!empty($filter->search), function ($q) use ($filter) {
                $q->where(function ($query) use ($filter) {
                    $query->where('name', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('address', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('email', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('phone', 'LIKE', '%' . $filter->search . '%');
                });
            })
            ->when(!empty($filter->role), function ($query) use ($filter) {
                return $query->where('role', 'LIKE', $filter->role . '%');
            })
            ->when(!empty($filter->orderBy), function ($query) use ($filter) {
                $query->orderBy($filter->orderBy, $filter->orderDirection);
            });

        return $data;
    }
}
