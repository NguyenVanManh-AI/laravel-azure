<?php

namespace App\Repositories;

use App\Models\TimeWork;
use Illuminate\Support\Facades\DB;
use Throwable;

class TimeWorkRepository extends BaseRepository implements TimeWorkInterface
{
    public function getModel()
    {
        return TimeWork::class;
    }

    public static function findById($id)
    {
        return (new self)->model->find($id);
    }

    public static function createTimeWork($data)
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

    public static function getTimeWork($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model
            ->when(!empty($filter->id_hospital), function ($query) use ($filter) {
                $query->where('id_hospital', $filter->id_hospital);
            });

        return $data;
    }

    public static function updateTimeWork($result, $data)
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
}
