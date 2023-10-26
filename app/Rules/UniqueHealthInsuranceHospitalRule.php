<?php

namespace App\Rules;

use App\Models\HealthInsuranceHospital;
use Illuminate\Contracts\Validation\Rule;

class UniqueHealthInsuranceHospitalRule implements Rule
{
    protected $id_health_insurance;

    protected $id_hospital;

    public function __construct($id_health_insurance, $id_hospital)
    {
        $this->id_health_insurance = $id_health_insurance;
        $this->id_hospital = $id_hospital;
    }

    public function passes($attribute, $value)
    {
        return !HealthInsuranceHospital::where('id_hospital', $this->id_hospital)
            ->where('id_health_insurance', $this->id_health_insurance)
            ->exists();
    }

    public function message()
    {
        return 'Bệnh viện đã chấp nhận loại bảo hiểm này rồi !';
    }
}
