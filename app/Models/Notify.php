<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_user',
        'title',
        'content',
    ];

    public function inforUser()
    {
        return $this->belongsTo(InforUser::class);
    }
}
