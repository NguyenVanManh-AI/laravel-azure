<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InforHospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_hospital',
        'province_code',
        'infrastructure',
        'description',
        'location',
        'search_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'token_verify_email',
    ];

    public function inforDoctors()
    {
        return $this->hasMany(InforDoctor::class);
    }

    public function hospitalDepartments()
    {
        return $this->hasMany(HospitalDepartment::class);
    }

    public function healthInsuranceHospitals()
    {
        return $this->hasMany(HealthInsuranceHospital::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function timeWork()
    {
        return $this->hasOne(TimeWork::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
