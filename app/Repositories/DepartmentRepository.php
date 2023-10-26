<?php

namespace App\Repositories;

use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Throwable;

class DepartmentRepository extends BaseRepository implements DepartmentInterface
{
    public function getModel()
    {
        return Department::class;
    }

    public static function findById($id)
    {
        return (new self)->model->find($id);
    }

    public static function createDepartment($data)
    {
        DB::beginTransaction();
        try {
            $newDepartment = (new self)->model->create($data);
            DB::commit();

            return $newDepartment;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public static function updateDepartment($result, $data)
    {
        DB::beginTransaction();
        try {
            $result->update($data);
            DB::commit();

            return $result;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public static function searchDepartment($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model
            ->when(!empty($filter->search), function ($q) use ($filter) {
                $q->where(function ($query) use ($filter) {
                    $query->where('name', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('description', 'LIKE', '%' . $filter->search . '%');
                });
            })
            ->when(!empty($filter->orderBy), function ($query) use ($filter) {
                $query->orderBy($filter->orderBy, $filter->orderDirection);
            })
            ->when(!empty($filter->id_departments), function ($query) use ($filter) {
                $query->whereNotIn('departments.id', $filter->id_departments);
            });

        return $data;
    }

    public static function getDepartment($filter)
    {
    }
}
