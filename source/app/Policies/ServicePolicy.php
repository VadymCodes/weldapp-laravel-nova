<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Service;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user can create services.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function index(User $user, User $mechanic)
    {
        if ($user->id !== $mechanic->id
            && $user->role == User::$ROLE_MECHANIC) {
            return false;
        }

        return true;
    }

    public function view(User $user, Service $service)
    {
        return $user->role == 'admin';
    }

    public function create(User $user)
    {
        return $user->role == 'admin';
    }

    public function update(User $user, Service $service)
    {
        return $user->role == 'admin';
    }

    public function delete(User $user, Service $service)
    {
        return $user->role == 'admin';
    }
}
