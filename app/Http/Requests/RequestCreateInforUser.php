<?php

namespace App\Http\Requests;

use App\Rules\UniqueUsernameForRole;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RequestCreateInforUser extends FormRequest
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
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100',
            'username' => 'string|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'address' => 'string|min:1',
            'date_of_birth' => 'string|min:1',
            'phone' => 'min:9|numeric',
            'gender' => 'in:0,1,2',
            // 'username' => ['required', 'string', 'max:100', new UniqueUsernameForRole('user')],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $errors,
            'errors' => $validator->errors(),
            'status' => 422,
        ], 422));
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required',
            'body.required' => 'Body is required',
        ];
    }
}
