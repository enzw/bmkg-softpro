<?php

namespace App\Policies;

use App\Models\JasaKonsultasi;
use App\Models\User;

class JasaKonsultasiPolicy
{
    /**
     * Perform pre-authorization checks
     */
    public function before(User $user): bool|null
    {
        if ($user->role === 'superuser' || $user->role === 'admin' || $user->role === 'superadmin') {
            return true;
        }
        return null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, JasaKonsultasi $jasaKonsultasi): bool
    {
        // Regular users can view their own records
        return $user->id === $jasaKonsultasi->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JasaKonsultasi $jasaKonsultasi): bool
    {
        // Regular users can delete their own records
        return $user->id === $jasaKonsultasi->user_id;
    }
}
