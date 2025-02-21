<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Permission;

class UserPolicy
{
    /**
     * Determine if the user can edit another user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return bool
     */
    public function editUser(User $user)
    {
        return $user->hasPermissionTo('edit-user');
    }

    /**
     * Determine if the user can create a new user.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function createUser(User $user)
    {
        return $user->hasPermissionTo('create-user');
    }

    /**
     * Determine if the user can remove another user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return bool
     */
    public function removeUser(User $user)
    {
        return $user->hasPermissionTo('remove-user');
    }

    /**
     * Determine if the user can reset another user's password.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function resetPassword(User $user)
    {
        return $user->hasPermissionTo('reset-password-for-users');
    }
}
