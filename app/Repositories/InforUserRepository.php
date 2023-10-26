<?php

namespace App\Repositories;

use App\Models\InforUser;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Class ExampleRepository.
 */
class InforUserRepository extends BaseRepository implements InforUserInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function getModel()
    {
        return InforUser::class;
    }

    public static function getInforUser($filter)
    {
        $filter = (object) $filter;
        $user = (new self)->model
            ->when(!empty($filter->id), function ($q) use ($filter) {
                $q->where('id', $filter->id);
            })
            ->when(!empty($filter->id_user), function ($q) use ($filter) {
                $q->where('id_user', $filter->id_user);
            })
            ->when(!empty($filter->google_id), function ($q) use ($filter) {
                $q->where('google_id', $filter->google_id);
            })
            ->when(!empty($filter->facebook_id), function ($q) use ($filter) {
                $q->where('facebook_id', $filter->facebook_id);
            });

        return $user;
    }

    public static function updateInforUser($id, $data)
    {
        DB::beginTransaction();
        try {
            $inforUser = (new self)->model->find($id);
            $inforUser->update($data);
            DB::commit();

            return $inforUser;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public static function createInforUser($data)
    {
        DB::beginTransaction();
        try {
            $newInforUser = (new self)->model->create($data);
            DB::commit();

            return $newInforUser;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
}
