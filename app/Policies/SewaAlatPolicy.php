<?php

namespace App\Policies;

use App\Models\SewaAlat;
use App\Models\User;

class SewaAlatPolicy
{
    /**
     * Perform pre-authorization checks
     */
    public function before(User $user): bool|null
    {
        // Super admin can do anything
        if ($user->role === 'superuser' || $user->role === 'superadmin') {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SewaAlat $sewaAlat): bool
    {
        // Admin can view all records
        if ($user->role === 'admin') {
            return true;
        }
        
        // Regular users can view their own records
        return $user->id === $sewaAlat->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SewaAlat $sewaAlat): bool
    {
        // Admin can update all records
        if ($user->role === 'admin') {
            return true;
        }
        
        // Regular users can update their own records
        return $user->id === $sewaAlat->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SewaAlat $sewaAlat): bool
    {
        // Admin can delete all records
        if ($user->role === 'admin') {
            return true;
        }
        
        // Regular users can delete their own records
        return $user->id === $sewaAlat->user_id;
    }
}
