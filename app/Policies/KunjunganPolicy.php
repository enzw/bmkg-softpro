<?php

namespace App\Policies;

use App\Models\Kunjungan;
use App\Models\User;

class KunjunganPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Kunjungan $kunjungan): bool
    {
        // Admin can view all records
        if ($user->role === 'admin') {
            return true;
        }
        
        // Regular users can view their own records or records with null user_id
        return $kunjungan->user_id === null || $user->id === $kunjungan->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Kunjungan $kunjungan): bool
    {
        // Admin can update all records
        if ($user->role === 'admin') {
            return true;
        }
        
        // Regular users can update their own records or records with null user_id
        return $kunjungan->user_id === null || $user->id === $kunjungan->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Kunjungan $kunjungan): bool
    {
        // Admin can delete all records
        if ($user->role === 'admin') {
            return true;
        }
        
        // Regular users can delete their own records or records with null user_id
        return $kunjungan->user_id === null || $user->id === $kunjungan->user_id;
    }
}
