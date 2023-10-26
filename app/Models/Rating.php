<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_work_schedule',
        'number_rating',
        'detail_rating',
    ];

    public function workSchedule()
    {
        return $this->belongsTo(WorkSchedule::class);
    }
}
