<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user'); 
        return [
            'entityCode' => ['required',Rule::exists('hierarchy_entities', 'code')], 
            'charge'=> ['required','max:100'],
            'name'=> ['required'],
            'lastName'=> ['required'],
            'ci'=> ['required',Rule::unique(User::class)->ignore($userId)],
            'phoneNumber'=> ['required','max:13'],
            'address'=> ['required','max:100'],
            'email'=> ['required','email',Rule::unique(User::class)->ignore($userId)],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
