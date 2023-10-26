<?php

namespace App\Repositories;

use App\Models\HealthInsuranceHospital;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthInsuranceHospitalRepository extends BaseRepository implements HealthInsuranceHospitalInterface
{
    public function getModel()
    {
        return HealthInsuranceHospital::class;
    }

    public static function findById($id)
    {
        return (new self)->model->find($id);
    }

    public static function getHealInsurHos($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model
            ->when(!empty($filter->id), function ($q) use ($filter) {
                $q->where('id', $filter->id);
            })
            ->when(!empty($filter->id_hospital), function ($q) use ($filter) {
                $q->where('id_hospital', $filter->id_hospital);
            })
            ->when(!empty($filter->id_health_insurance), function ($q) use ($filter) {
                $q->where('id_health_insurance', $filter->id_health_insurance);
            });

        return $data;
    }

    public static function createHealInsurHos($data)
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

    public static function searchHealInsurHos($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model->selectRaw('health_insurance_hospitals.id as id_health_insurance_hospital,
        health_insurances.id as id_health_insurance, health_insurance_hospitals.* , health_insurances.*')
            ->join('health_insurances', 'health_insurance_hospitals.id_health_insurance', '=', 'health_insurances.id')
            ->when(!empty($filter->search), function ($q) use ($filter) {
                $q->where(function ($query) use ($filter) {
                    $query->where('name', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('description', 'LIKE', '%' . $filter->search . '%');
                });
            })
            ->when(!empty($filter->id_hospital), function ($q) use ($filter) {
                $q->where('health_insurance_hospitals.id_hospital', $filter->id_hospital);
            })
            ->when(!empty($filter->id), function ($q) use ($filter) {
                $q->where('health_insurance_hospitals.id', $filter->id);
            })
            ->when(!empty($filter->orderBy), function ($query) use ($filter) {
                $query->orderBy($filter->orderBy, $filter->orderDirection);
            });

        return $data;
    }
}
