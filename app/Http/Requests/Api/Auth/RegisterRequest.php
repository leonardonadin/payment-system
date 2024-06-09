<?php

namespace App\Http\Requests\Api\Auth;

use App\Enums\CountryCodes;
use App\Enums\UserTypes;
use App\Rules\DocumentRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'document' => ['required', 'string', 'max:14', 'unique:users,document', new DocumentRule],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['sometimes', 'string', Rule::enum(UserTypes::class)],
            'country_code' => ['sometimes', 'string', 'max:2', Rule::enum(CountryCodes::class)],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'document.unique' => 'The document has an error.',
            'email.unique' => 'The email has an error.',
        ];
    }
}
