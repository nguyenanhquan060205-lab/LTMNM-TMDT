<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Complaint;
use App\Models\User;

class ComplaintPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Complaint $complaint): bool
    {
        return $user->id === $complaint->user_id || $user->role === UserRole::Admin;
    }

    public function create(User $user): bool
    {
        return ! $user->is_locked;
    }

    public function update(User $user, Complaint $complaint): bool
    {
        return $user->role === UserRole::Admin;
    }
}
