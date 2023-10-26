<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthInsuranceHospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_hospital',
        'id_health_insurance',
    ];

    public function inforHospital()
    {
        return $this->belongsTo(InforHospital::class);
    }

    public function healthInsurances()
    {
        return $this->belongsTo(HealthInsurance::class);
    }
}
