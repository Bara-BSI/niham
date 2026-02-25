<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Super admin can do everything.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return null;
    }

    private function canManageUser(User $authUser, User $model): bool
    {
        if ($authUser->isSuperAdmin() || $authUser->isRole('admin')) {
            return true;
        }

        if ($authUser->property_id !== $model->property_id) {
            return false;
        }

        if (!$authUser->hasExecutiveOversight()) {
            if ($authUser->department_id !== $model->department_id) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('perm_users', 'view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        if (!$this->canManageUser($user, $model)) return false;
        return $user->hasPermission('perm_users', 'view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('perm_users', 'create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if (!$this->canManageUser($user, $model)) return false;
        return $user->hasPermission('perm_users', 'update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if (!$this->canManageUser($user, $model)) return false;
        return $user->hasPermission('perm_users', 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        if (!$this->canManageUser($user, $model)) return false;
        return $user->hasPermission('perm_users', 'delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        if (!$this->canManageUser($user, $model)) return false;
        return $user->hasPermission('perm_users', 'delete');
    }
}
