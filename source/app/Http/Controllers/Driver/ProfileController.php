<?php

namespace App\Http\Controllers\Driver;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\DriverResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProfileController extends Controller
{
    /**
     * Get a Drivers profile.
     *
     * @param \App\Models\User
     * @return App\Http\Resources\DriverResource
     */
    public function __invoke(User $user)
    {
        if ($user->role == User::$ROLE_MECHANIC) {
            throw new ModelNotFoundException();
        }

        $this->authorize('showDriverProfile', [User::class, $user]);

        return new DriverResource($user);
    }
}
