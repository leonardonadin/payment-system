<?php

namespace App\Http\Requests\Api;

use App\Contracts\Services\WalletServiceContract;
use Illuminate\Foundation\Http\FormRequest;

class WalletUpdateRequest extends FormRequest
{
    public function __construct(private WalletServiceContract $walletService)
    {
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $wallet = $this->walletService->getWallet($this->route('wallet_id'));

        return auth()->check() && auth()->user()->id == $wallet->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'in:in,out'],
            'amount' => ['required', 'numeric', 'min:0.01']
        ];
    }
}
