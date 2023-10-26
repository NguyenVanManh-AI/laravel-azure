<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class UniqueUsernameForRole implements Rule
{
    public $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function passes($attribute, $value)
    {
        return !User::where($attribute, $value)->where('role', $this->role)->exists();
    }

    public function message()
    {
        return "The :attribute must be unique for $this->role.";
    }
}
