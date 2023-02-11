<?php

namespace App\Http\Requests\API\Customer\AuthController;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers',
            'phone_number' => 'string|max:13',
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters() // Require at least one letter
                    ->mixedCase() // Require at least one uppercase and one lowercase letter
                    ->numbers() // Require at least one number
            ],
        ];
    }
}
