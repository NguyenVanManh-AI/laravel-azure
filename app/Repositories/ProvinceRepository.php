<?php

namespace App\Repositories;

use App\Models\Province;

class ProvinceRepository extends BaseRepository implements ProvinceInterface
{
    public function getModel()
    {
        return Province::class;
    }

    public static function getProvince($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model->selectRaw('provinces.*')
            ->when(!empty($filter->province_code), function ($query) use ($filter) {
                return $query->where('provinces.province_code', '=', $filter->province_code);
            });

        return $data;
    }
}
