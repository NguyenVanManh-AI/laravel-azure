<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'id',
        'email',
        'password',
        'username',
        'name',
        'phone',
        'address',
        'avatar',
        'is_accept',
        'role',
        'email_verified_at',
        'remember_token',
        'token_verify_email',
    ];

    public function notifies()
    {
        return $this->hasMany(Notify::class);
    }

    public function workSchedules()
    {
        return $this->hasMany(WorkSchedule::class);
    }

    public function inforUser()
    {
        return $this->hasOne(InforUser::class);
    }

    public function inforDoctor()
    {
        return $this->hasOne(InforDoctor::class);
    }

    public function inforHospital()
    {
        return $this->hasOne(InforHospital::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
        'token_verify_email',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
