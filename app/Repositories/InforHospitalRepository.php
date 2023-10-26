<?php

namespace App\Repositories;

use App\Models\InforHospital;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Class ExampleRepository.
 */
class InforHospitalRepository extends BaseRepository implements InforHospitalInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function getModel()
    {
        return InforHospital::class;
    }

    public static function getInforHospital($filter)
    {
        $filter = (object) $filter;
        $user = (new self)->model
            ->when(!empty($filter->id), function ($q) use ($filter) {
                $q->where('id', $filter->id);
            })
            ->when(!empty($filter->id_hospital), function ($q) use ($filter) {
                $q->where('id_hospital', $filter->id_hospital);
            });

        return $user;
    }

    public static function createHospital($data)
    {
        DB::beginTransaction();
        try {
            $newHospital = (new self)->model->create($data);
            DB::commit();

            return $newHospital;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public static function updateInforHospital($id, $data)
    {
        DB::beginTransaction();
        try {
            $inforHospital = (new self)->model->find($id);
            $inforHospital->update($data);
            DB::commit();

            return $inforHospital;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public static function updateHospital($result, $data)
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

    public static function searchHospital($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model->selectRaw('users.*, infor_hospitals.*')
            ->join('users', 'users.id', '=', 'infor_hospitals.id_hospital')
            ->when(!empty($filter->search), function ($q) use ($filter) {
                $q->where(function ($query) use ($filter) {
                    $query->where('users.name', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('users.address', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('users.username', 'LIKE', '%' . $filter->search . '%');
                });
            })
            ->when(isset($filter->is_accept), function ($query) use ($filter) {
                if ($filter->is_accept === 'both') {
                } else {
                    $query->where('users.is_accept', $filter->is_accept);
                }
            })
            ->when(isset($filter->province_code), function ($query) use ($filter) {
                $query->where('infor_hospitals.province_code', $filter->province_code);
            })
            ->when(!empty($filter->orderBy), function ($query) use ($filter) {
                $query->orderBy($filter->orderBy, $filter->orderDirection);
            });

        return $data;
    }
}
