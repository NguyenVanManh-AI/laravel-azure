<?php

namespace App\Repositories;

use App\Models\HealthInsurance;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthInsuranceRepository extends BaseRepository implements HealthInsuranceInterface
{
    public function getModel()
    {
        return HealthInsurance::class;
    }

    public static function findById($id)
    {
        return (new self)->model->find($id);
    }

    public static function createHealthInsur($data)
    {
        DB::beginTransaction();
        try {
            $newHealthInsur = (new self)->model->create($data);
            DB::commit();

            return $newHealthInsur;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public static function updateHealthInsur($result, $data)
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

    public static function searchHealthInsur($filter)
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
            });

        return $data;
    }

    // public static function getHealthInsur($filter)
    // {
    // }
}
