<?php

namespace App\Rules;

use App\Models\HospitalDepartment;
use Illuminate\Contracts\Validation\Rule;

class UniqueHospitalDepartmentRule implements Rule
{
    protected $id_department;

    protected $id_hospital;

    public function __construct($id_department, $id_hospital)
    {
        $this->id_department = $id_department;
        $this->id_hospital = $id_hospital;
    }

    public function passes($attribute, $value)
    {
        // Kiểm tra xem cặp id_hospital và id_department đã tồn tại trong bảng hospital_department chưa
        // Chỉ cho phép một cặp duy nhất id_hospital và id_department
        return !HospitalDepartment::where('id_hospital', $this->id_hospital)
            ->where('id_department', $this->id_department)
            ->exists();
    }

    public function message()
    {
        return 'Bệnh viện đã có khoa này rồi !';
    }
}
