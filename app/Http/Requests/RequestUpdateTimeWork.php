<?php

namespace App\Http\Requests;

use App\Rules\ValidTimeAfternoon;
use App\Rules\ValidTimeMorning;
use App\Rules\ValidTimeNight;
use App\Rules\ValidTimeRange;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RequestUpdateTimeWork extends FormRequest
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
            'times' => 'required',
            // 'times.*.*.time' => ['required', 'array', 'size:2', new ValidTimeRange],
            'times.*.morning.time' => ['required', 'array',  new ValidTimeMorning],
            'times.*.afternoon.time' => ['required', 'array',  new ValidTimeAfternoon],
            'times.*.night.time' => ['required', 'array',  new ValidTimeNight],
            'enable' => 'required',
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
