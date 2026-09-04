<?php

namespace App\Policies;

use App\Models\MoneyRequests\MoneyRequest;
use App\Models\User;

class MoneyRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MoneyRequest $moneyRequest): bool
    {
        return $user->id === $moneyRequest->user_id || $user->can([
            'manage_money_requests', 'fulfill_money_requests',
        ]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MoneyRequest $moneyRequest): bool
    {
        return $user->id === $moneyRequest->user_id && $moneyRequest->status->isPending();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MoneyRequest $moneyRequest): bool
    {
        return $user->id === $moneyRequest->user_id && $moneyRequest->status->isPending();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MoneyRequest $moneyRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MoneyRequest $moneyRequest): bool
    {
        return false;
    }
}
