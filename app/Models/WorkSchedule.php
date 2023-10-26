<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_doctor',
        'id_user',
        'id_service',
        'price',
        'time',
        'content',
    ];

    public function inforUser()
    {
        return $this->belongsTo(InforUser::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function inforDoctor()
    {
        return $this->belongsTo(InforDoctor::class);
    }
}
