<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
