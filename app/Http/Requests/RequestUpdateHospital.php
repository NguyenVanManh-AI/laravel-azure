<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RequestUpdateHospital extends FormRequest
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
        // $userId = $this->route('user');
        $userId = Auth::guard('user_api')->user()->id;

        return [
            'name' => 'required|string|between:2,100',
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users')->ignore($userId)],
            'username' => ['required', 'string', 'max:100', Rule::unique('users')->ignore($userId)],
            'address' => 'required|string|min:1',
            'province_code' => 'required|integer',
            'phone' => 'required|min:9|numeric',
            'infrastructure' => 'required',
            'description' => 'required',
            'location' => 'required',
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
