<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InforExtendDoctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_doctor',
        'prominent',
        'information',
        'strengths',
        'work_experience',
        'training_process',
        'language',
        'awards_recognition',
        'research_work',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'token_verify_email',
    ];

    public function inforDoctor()
    {
        return $this->belongsTo(InforDoctor::class);
    }
}
