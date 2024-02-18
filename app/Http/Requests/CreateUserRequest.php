<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
class CreateUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'entityCode' => ['required',Rule::exists('hierarchy_entities', 'code'),], 
            'charge'=> ['required','max:100'],
            'name'=> ['required'],
            'lastName'=> ['required'],
            'ci'=> ['required','unique:users'],
            'phoneNumber'=> ['required','max:13'],
            'address'=> ['required','max:100'],
            'email'=> ['required','email','unique:users'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
