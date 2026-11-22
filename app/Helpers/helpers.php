<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

if (! function_exists('number_format_decimal')) {
    /**
     * Format an integer or decimal string without converting it to a float.
     */
    function number_format_decimal(
        string|int $number,
        int $decimals = 0,
        string $decimal_separator = '.',
        string $thousands_separator = ',',
    ): string {
        if ($decimals < 0) {
            throw new InvalidArgumentException('The number of decimal places must not be negative.');
        }

        $number = (string) $number;

        if (! preg_match('/^([+-]?)(?:([0-9]+)(?:\.([0-9]*))?|\.([0-9]+))$/D', $number, $matches)) {
            throw new InvalidArgumentException('The value must be a plain integer or decimal string.');
        }

        $negative = $matches[1] === '-';
        $whole    = ($matches[2] ?? '') !== '' ? $matches[2] : '0';
        $fraction = ($matches[3] ?? '') !== '' ? $matches[3] : ($matches[4] ?? '');
        $whole    = ltrim($whole, '0') ?: '0';
        $scaled   = ltrim($whole.str_pad(substr($fraction, 0, $decimals), $decimals, '0'), '0') ?: '0';

        if (isset($fraction[$decimals]) && $fraction[$decimals] >= '5') {
            for ($index = strlen($scaled) - 1; $index >= 0; $index--) {
                if ($scaled[$index] !== '9') {
                    $scaled[$index] = (string) ((int) $scaled[$index] + 1);

                    break;
                }

                $scaled[$index] = '0';

                if ($index === 0) {
                    $scaled = '1'.$scaled;
                }
            }
        }

        if ($decimals > 0) {
            $scaled = str_pad($scaled, $decimals + 1, '0', STR_PAD_LEFT);
            $whole  = substr($scaled, 0, -$decimals);
            $fraction = substr($scaled, -$decimals);
        } else {
            $whole    = $scaled;
            $fraction = '';
        }

        $whole = preg_replace_callback(
            '/\B(?=(\d{3})+(?!\d))/',
            fn (array $matches): string => $thousands_separator,
            $whole,
        );

        $sign = $negative && trim($scaled, '0') !== '' ? '-' : '';

        return $sign.$whole.($decimals > 0 ? $decimal_separator.$fraction : '');
    }
}

if (! function_exists('can')) {
    function can(string|array $permission): bool
    {
        return Auth::user()?->can($permission) ?? false;
    }
}

if (! function_exists('cannot')) {
    function cannot(string|array $permission): bool
    {
        return ! can($permission);
    }
}

if (! function_exists('auth_user_owns')) {
    function auth_user_owns(Model $model, string $column = 'user_id'): bool
    {
        $userId = Auth::id();
        $value  = $model->getAttribute($column);

        if ($userId === null || blank($value)) {
            return false;
        }

        return (int) $userId === (int) $value;
    }
}
