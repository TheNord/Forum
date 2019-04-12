<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the own profile
     *
     * @param User $user
     * @param User $signedInUser
     * @return boolean
     */
    public function update(User $user, User $signedInUser): bool
    {
        return $user->id === $signedInUser->id;
    }
}
