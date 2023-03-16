<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class IsbnRule implements ValidationRule
{
    private const ISBN_REGEX = '/^(\d{10}|\d{13})$/';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (preg_match(self::ISBN_REGEX, $value) !== 1) {
            $fail('The :attribute must be 10 or 13 digits.');
        }
    }
}
