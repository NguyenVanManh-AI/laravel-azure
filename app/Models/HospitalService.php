<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalService extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_hospital_department',
        'name',
        'time_advise',
        'price',
        'infor',
    ];

    public function hospitalDepartment()
    {
        return $this->belongsTo(HospitalDepartment::class);
    }
}
