<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UrlRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'long_url'  => 'required|url',
            'single_use'=> 'required|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'long_url.required' => 'Please enter long url',
            'long_url.url'      => 'Please enter valid URL',
            'single_use.required' => 'Mention if the URL is single use or not',
            'single_use.boolean'      => 'Please mention true/false',
        ];
    }

    /*** Get the error messages for the defined validation rules.** @return array*/
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors(),'success' => false], 422));
    }
}
