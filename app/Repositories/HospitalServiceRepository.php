<?php

namespace App\Repositories;

use App\Models\HospitalService;
use Illuminate\Support\Facades\DB;
use Throwable;

class HospitalServiceRepository extends BaseRepository implements HospitalServiceInterface
{
    public function getModel()
    {
        return HospitalService::class;
    }

    public static function findById($id)
    {
        return (new self)->model->find($id);
    }

    public static function getHospitalService($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model
            ->when(!empty($filter->id_hospital_department), function ($query) use ($filter) {
                $query->where('id_hospital_department', '=', $filter->id_hospital_department);
            })
            ->when(!empty($filter->search), function ($query) use ($filter) {
                $query->where('hospital_services.name', 'LIKE', '%' . $filter->search . '%');
            });

        return $data;
    }

    public static function updateHospitalService($result, $data)
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

    public static function createHospitalService($data)
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

    public static function searchAll($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model->selectRaw(
            'hospital_services.*, hospital_departments.*,
            hospital_services.id as id_hospital_service, 
            hospital_departments.id as id_hospital_departments,
            hospital_services.time_advise as time_advise_hospital_service, 
            hospital_departments.time_advise as time_advise_hospital_departments,
            hospital_services.price as price_hospital_service, 
            hospital_departments.price as price_hospital_departments,
            
            users_hospital.name as name_hospital, departments.name as name_department,
            departments.thumbnail as thumbnail_department,infor_hospitals.province_code as provider_code_service,
            infor_hospitals.cover_hospital as cover_hospital
            
            ')
            ->join('hospital_departments', 'hospital_departments.id', '=', 'hospital_services.id_hospital_department')
            ->join('users as users_hospital', 'users_hospital.id', '=', 'hospital_departments.id_hospital')
            ->join('infor_hospitals', 'infor_hospitals.id_hospital', '=', 'users_hospital.id')
            ->join('departments', 'departments.id', '=', 'hospital_departments.id_department')

            ->when(!empty($filter->search), function ($q) use ($filter) {
                $q->where(function ($query) use ($filter) {
                    $query->where('hospital_services.name', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('users_hospital.name', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('departments.name', 'LIKE', '%' . $filter->search . '%');
                });
            })
            ->when(!empty($filter->province_code), function ($query) use ($filter) {
                return $query->where('infor_hospitals.province_code', $filter->province_code);
            })
            ->when(!empty($filter->id_hospital), function ($query) use ($filter) {
                return $query->where('infor_hospitals.id_hospital', $filter->id_hospital);
            })
            ->when(!empty($filter->orderBy), function ($query) use ($filter) {
                $query->orderBy($filter->orderBy, $filter->orderDirection);
            })

            ->when(!empty($filter->id_hospital_services), function ($query) use ($filter) {
                return $query->where('hospital_services.id', $filter->id_hospital_services);
            })

            ->when(isset($filter->is_delete), function ($query) use ($filter) {
                return $query->where('hospital_services.is_delete', $filter->is_delete);
            });

        return $data;
    }
}

/*
isset($filter->is_delete) => 0 hay 1 đều được đem ra so sánh nghĩa là chỉ cần có tạo biến là được ,
nếu như bình thường !empty($filter->is_delete) => 0 sẽ là empty => không query luôn

không nên dùng $filter->is_delete !== null vì nếu như này ta không truyền biến vào thì nó sẽ báo lỗi

->when(isset($filter->is_delete), function ($query) use ($filter) {
    return $query->where('hospital_services.is_delete', $filter->is_delete);
});

isset($filter->is_delete)
    + không truyền vào 'is_delete' hoặc truyền vào 'is_delete' => null
        => Thì sẽ không làm gì cả

    + 'is_delete' => 0 hoặc 'is_delete' => 'avsdvsd'
        => Thì nó sẽ đem đi so sánh với giá trị 0

    + 'is_delete' => 1 (Đã xóa)
        => Thì nó sẽ đem đi so sánh với giá trị 1
*/
