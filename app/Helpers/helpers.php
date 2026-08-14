<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('can')) {
    function can(string|array $permission): bool
    {
        return Auth::user()?->can($permission) ?? false;
    }
}

if (!function_exists('cannot')) {
    function cannot(string|array $permission): bool
    {
        return !can($permission);
    }
}
