<?php

namespace App\Rules\Documents;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BrRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = preg_replace('/[^0-9]/', '', $value);

        if (request()->has('type') && in_array(request()->get('type'), ['merchant', 'common'])) {
            $this->validateByType($value, $fail);
            return;
        }

        $this->validateByLength($value, $fail);
    }

    private function validateByType(string $value, Closure $fail): void
    {
        if (request()->get('type') == 'merchant') {
            $this->validateCnpj($value, $fail);
            return;
        }

        if (request()->get('type') == 'common') {
            $this->validateCpf($value, $fail);
            return;
        }
    }

    private function validateByLength(string $value, Closure $fail): void
    {
        if (strlen($value) === 11) {
            $this->validateCpf($value, $fail);
        } elseif (strlen($value) === 14) {
            $this->validateCnpj($value, $fail);
        } else {
            $fail('The document is invalid.');
        }
    }

    private function validateCpf(string $value, Closure $fail): void
    {
        if (strlen($value) !== 11) {
            $fail('The CPF is invalid.');
            return;
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $value[$i] * (10 - $i);
        }

        $remainder = $sum % 11;
        $digit = $remainder < 2 ? 0 : 11 - $remainder;

        if ($value[9] != $digit) {
            $fail('The CPF is invalid.');
            return;
        }

        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $value[$i] * (11 - $i);
        }

        $remainder = $sum % 11;
        $digit = $remainder < 2 ? 0 : 11 - $remainder;

        if ($value[10] != $digit) {
            $fail('The CPF is invalid.');
            return;
        }
    }

    private function validateCnpj(string $value, Closure $fail): void
    {
        if (strlen($value) !== 14) {
            $fail('The CNPJ is invalid.');
            return;
        }

        $sum = 0;
        $weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 12; $i++) {
            $sum += $value[$i] * $weights[$i];
        }

        $remainder = $sum % 11;
        $digit = $remainder < 2 ? 0 : 11 - $remainder;

        if ($value[12] != $digit) {
            $fail('The CNPJ is invalid.');
            return;
        }

        $sum = 0;
        $weights = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 13; $i++) {
            $sum += $value[$i] * $weights[$i];
        }

        $remainder = $sum % 11;
        $digit = $remainder < 2 ? 0 : 11 - $remainder;

        if ($value[13] != $digit) {
            $fail('The CNPJ is invalid.');
            return;
        }
    }
}
