<?php

namespace App\Repositories;

use App\Models\HospitalDepartment;
use Illuminate\Support\Facades\DB;
use Throwable;

class HospitalDepartmentRepository extends BaseRepository implements HospitalDepartmentInterface
{
    public function getModel()
    {
        return HospitalDepartment::class;
    }

    public static function findById($id)
    {
        return (new self)->model->find($id);
    }

    public static function getHospitalDepartment($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model
            ->when(!empty($filter->id), function ($q) use ($filter) {
                $q->where('id', $filter->id);
            })
            ->when(!empty($filter->id_department), function ($q) use ($filter) {
                $q->where('id_department', $filter->id_department);
            })
            ->when(!empty($filter->id_hospital), function ($q) use ($filter) {
                $q->where('id_hospital', $filter->id_hospital);
            });

        return $data;
    }

    public static function searchHospitalDepartment($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model
            ->join('departments', 'hospital_departments.id_department', '=', 'departments.id')

            ->when(!empty($filter->search), function ($q) use ($filter) {
                $q->where(function ($query) use ($filter) {
                    $query->where('name', 'LIKE', '%' . $filter->search . '%');
                });
            })
            ->when(!empty($filter->orderBy), function ($query) use ($filter) {
                $query->orderBy($filter->orderBy, $filter->orderDirection);
            })

            ->when(!empty($filter->id_hospital), function ($q) use ($filter) {
                $q->where('hospital_departments.id_hospital', $filter->id_hospital);
            })
            ->when(!empty($filter->id), function ($q) use ($filter) {
                $q->where('hospital_departments.id', $filter->id);
            })
            ->when(!empty($filter->id_department), function ($q) use ($filter) {
                $q->where('hospital_departments.id_department', $filter->id_department);
            })
            ->select('hospital_departments.id as id_hospital_departments', 'hospital_departments.*', 'departments.*');

        return $data;
    }

    public static function updateHospitalDepartment($result, $data)
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

    public static function createHosDepart($data)
    {
        DB::beginTransaction();
        try {
            $new = (new self)->model->create($data);
            DB::commit();

            return $new;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
}
