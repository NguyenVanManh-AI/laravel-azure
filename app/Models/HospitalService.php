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
        'thumbnail_service',
        'time_advise',
        'price',
        'infor',
        'search_number_service',
        'is_delete',
    ];

    public function hospitalDepartment()
    {
        return $this->belongsTo(HospitalDepartment::class);
    }
}
