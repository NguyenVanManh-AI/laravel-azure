<?php

namespace App\Http\Requests;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Foundation\Http\FormRequest;

class RequestCreatePassword extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'new_password' => 'required|string|min:6|confirmed',
        ];
    }

    public function withValidator($validator)
    {
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                Toastr::error($error);
            }
        }
    }
}
