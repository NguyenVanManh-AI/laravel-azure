<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeWork extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_hospital',
        'times',
        'enable',
        'note',
    ];

    public function inforHospital()
    {
        return $this->belongsTo(InforHospital::class);
    }
}
