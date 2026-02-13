<?php

namespace App\Policies;

use App\Models\Magang;
use App\Models\User;

class MagangPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user): bool|null
    {
        if ($user->role === 'superuser' || $user->role === 'admin' || $user->role === 'superadmin') {
            return true;
        }
        return null;
    }

    /**
     * Determine whether the user can view the magang.
     */
    public function view(User $user, Magang $magang): bool
    {
        return $user->id === $magang->user_id;
    }

    /**
     * Determine whether the user can delete the magang.
     */
    public function delete(User $user, Magang $magang): bool
    {
        return $user->id === $magang->user_id;
    }
}
