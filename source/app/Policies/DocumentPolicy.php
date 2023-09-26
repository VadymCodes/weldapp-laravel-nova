<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Document $resource)
    {
        return $user->role == 'admin';
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, Document $resource)
    {
        return $user->role == 'admin';
    }

    public function delete(User $user, Document $resource)
    {
        return $user->role == 'admin';
    }
}
