<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InforDoctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_doctor',
        'id_department',
        'id_hospital',
        'is_confirm',
        'province_code',
        'date_of_birth',
        'experience',
        'gender',
        'search_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'token_verify_email',
    ];

    public function workSchedules()
    {
        return $this->hasMany(WorkSchedule::class);
    }

    public function vacationSchedules()
    {
        return $this->hasMany(VacationSchedule::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function inforHospital()
    {
        return $this->belongsTo(InforHospital::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
