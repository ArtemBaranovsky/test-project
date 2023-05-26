<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $this->hasAccess($user);
    }

    public function update(User $user, Subscription $subscription): bool
    {
        return $this->hasAccess($user);
    }

    public function delete(User $user, Subscription $subscription): bool
    {
        return $this->hasAccess($user);
    }

    private function hasAccess(User $user): bool
    {
        $allowedRoles = ['publisher', 'moderator', 'admin'];
        return in_array($user->role, $allowedRoles);
    }
}
