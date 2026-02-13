<?php

namespace App\Policies;

use App\Models\Asuransi;
use App\Models\User;

class AsuransiPolicy
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
     * Determine whether the user can view the asuransi.
     */
    public function view(User $user, Asuransi $asuransi): bool
    {
        return $user->id === $asuransi->user_id;
    }

    /**
     * Determine whether the user can delete the asuransi.
     */
    public function delete(User $user, Asuransi $asuransi): bool
    {
        return $user->id === $asuransi->user_id;
    }
}
