<?php

namespace App\Rules\Documents;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        throw new \Exception('This rule is not implemented yet.');
    }
}
