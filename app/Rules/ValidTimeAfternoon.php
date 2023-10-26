<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidTimeAfternoon implements Rule
{
    public function passes($attribute, $value)
    {
        $startTime = strtotime($value[0]);
        $endTime = strtotime($value[1]);
        $minTime = strtotime('12:00');
        $maxTime = strtotime('18:00');

        if ($startTime === false || $endTime === false) {
            return false; // Thời gian không hợp lệ
        }

        // Kiểm tra thời gian theo yêu cầu của bạn
        if ($startTime >= $endTime || $startTime < $minTime || $endTime > $maxTime) { // (điều kiện sai)
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'Trường :attribute không phải là phạm vi thời gian hợp lệ.';
    }
}
