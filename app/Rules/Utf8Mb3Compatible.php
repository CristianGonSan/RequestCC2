<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class Utf8Mb3Compatible implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!\is_string($value)) {
            return;
        }

        if (\preg_match('/[\x{10000}-\x{10FFFF}]/u', $value) === 1) {
            $fail(__('validation.utf8_mb3_compatible', ['attribute' => $attribute]));
        }
    }
}
