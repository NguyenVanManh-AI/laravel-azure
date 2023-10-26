<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_user',
        'id_category',
        'title',
        'content',
        'thumbnail',
        'search_number',
        'is_accept',
        'is_show',
    ];

    public function inforDoctor()
    {
        return $this->belongsTo(InforDoctor::class);
    }

    public function inforHospital()
    {
        return $this->belongsTo(InforHospital::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
