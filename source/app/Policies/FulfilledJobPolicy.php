<?php

namespace App\Policies;

use App\Models\FulfilledJob;
use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FulfilledJobPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user can store a Fulfilled Job.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function store(User $user, Job $job)
    {
        if ($user->id !== $job->user->id) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the given user can view a Fulfilled Job.
     *
     * @param  \App\Models\User  $user
     *@param \App\Models\FulfilledJob $fulfilledJob
     * @return bool
     */
    public function show(User $user, FulfilledJob $fulfilledJob)
    {
        if ($user->id === $fulfilledJob->job->user->id) {
            return true;
        }

        if ($user->id === $fulfilledJob->bid->user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the given mechanic can fulfill the given Fulfilled job.
     *
     * @param  \App\Models\User  $user
     * @param \App\Models\FulfilledJob $fulfilledJob
     * @return bool
     */
    public function fulfill(User $user, FulfilledJob $fulfilledJob)
    {
        if ($user->id === $fulfilledJob->bid->user->id) {
            return true;
        }

        return false;
    }

    public function view(User $user, FulfilledJob $job)
    {
        return $user->role == 'admin';
    }

    public function update(User $user, FulfilledJob $job)
    {
        return $user->role == 'admin';
    }
}
