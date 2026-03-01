<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Property;

class PropertyPolicy
{
    /**
     * Only super admin can manage properties.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Property $property): bool
    {
        return strtolower((string) optional($user->role)->name) === 'admin' && $user->property_id === $property->id;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Property $property): bool
    {
        return strtolower((string) optional($user->role)->name) === 'admin' && $user->property_id === $property->id;
    }

    public function delete(User $user, Property $property): bool
    {
        return false;
    }
}
