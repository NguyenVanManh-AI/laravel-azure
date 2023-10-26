<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacationSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_doctor',
        'date',
        'shift_off',
        'is_accept',
    ];

    public function inforDoctor()
    {
        return $this->belongsTo(InforDoctor::class);
    }
}
