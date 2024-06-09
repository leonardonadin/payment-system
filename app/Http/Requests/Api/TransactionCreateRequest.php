<?php

namespace App\Http\Requests\Api;

use App\Enums\UserTypes;
use Illuminate\Foundation\Http\FormRequest;

class TransactionCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->type == UserTypes::COMMON;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payer.*' => ['sometimes', 'array'],
            'payer.wallet_id' => ['sometimes', 'integer', 'exists:wallets,id'],
            'payee.*' => ['required', 'array'],
            'payee.id' => ['required', 'integer', 'exists:users,id'],
            'payee.wallet_id' => ['sometimes', 'integer', 'exists:wallets,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['sometimes', 'string']
        ];
    }
}
