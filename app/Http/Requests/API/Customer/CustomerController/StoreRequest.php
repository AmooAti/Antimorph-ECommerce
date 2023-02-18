<?php

namespace App\Http\Requests\API\Customer\CustomerController;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreRequest extends FormRequest
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
            'first_name'   => ["required", "string"],
            'last_name'    => ["required", "string"],
            'email'        => ["required", "email", "unique:customers,email"],
            'phone_number' => ["required", "regex:/^09[0-9]{9}$/"],
            'is_suspend'   => [Rule::in(['active', 'suspended'])],
            'password'     => [
                "required", Password::min(8)
                    ->letters()   // Require at least one letter
                    ->mixedCase() // Require at least one uppercase and one lowercase letter
                    ->numbers()   // Require at least one number
            ]
        ];
    }
}
