<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user can access a mechanics profile.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function showMechanicProfile(User $user, User $mechanic)
    {
        if ($user->id !== $mechanic->id
            && $user->role == User::$ROLE_MECHANIC) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the given user can access a drivers profile.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function showDriverProfile(User $user, User $driver)
    {
        if ($user->id !== $driver->id
            && $user->role == User::$ROLE_DRIVER) {
            return false;
        }

        return true;
    }

    public function view(User $user, User $resource)
    {
        return $user->role == 'admin';
    }

    public function create(User $user)
    {
        return $user->role == 'admin';
    }

    public function update(User $user, User $resource)
    {
        return $user->role == 'admin';
    }

    public function delete(User $user, User $resource)
    {
        return $user->role == 'admin';
    }
}
