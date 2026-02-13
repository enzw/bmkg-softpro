<?php

namespace App\Policies;

use App\Models\Survey;
use App\Models\User;

class SurveyPolicy
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
    public function view(User $user, Survey $survey): bool
    {
        // Regular users can view their own records
        return $user->id === $survey->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Survey $survey): bool
    {
        // Regular users can delete their own records
        return $user->id === $survey->user_id;
    }
}
