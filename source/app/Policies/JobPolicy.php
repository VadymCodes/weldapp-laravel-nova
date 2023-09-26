<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user can view a Job.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function show(User $user, Job $job)
    {
        if ($user->id !== $job->user->id
            && $user->role == User::$ROLE_DRIVER
            && !$job->fulfilledJob()->exists()) {
            return false;
        }

        return true;
    }

    public function view(User $user, Job $job)
    {
        return $user->role == 'admin';
    }

    public function update(User $user, Job $job)
    {
        return $user->role == 'admin';
    }

    public function delete(User $user, Job $job)
    {
        return $user->role == 'admin';
    }
}
