<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DocumentRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $countryCode = 'BR';
        if (request()->has('country_code')) {
            $countryCode = request()->get('country_code');
        }

        $rule = match ($countryCode) {
            'BR' => 'App\Rules\Documents\BrRule',
            'CO' => 'App\Rules\Documents\CoRule',
            'US' => 'App\Rules\Documents\UsRule',
            default => 'App\Rules\Documents\BrRule',
        };

        (new $rule)->validate($attribute, $value, $fail);
    }
}
