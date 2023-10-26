<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalDepartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_department',
        'id_hospital',
        'time_advise',
        'price',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function inforHospital()
    {
        return $this->belongsTo(InforHospital::class);
    }

    public function hospitalServices()
    {
        return $this->hasMany(HospitalService::class);
    }
}
