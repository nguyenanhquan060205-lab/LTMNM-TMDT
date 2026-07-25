<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function create(User $user): bool
    {
        return ! $user->is_locked;
    }

    public function view(User $user, Review $review): bool
    {
        return true;
    }

    public function update(User $user, Review $review): bool
    {
        return false;
    }

    public function delete(User $user, Review $review): bool
    {
        return false;
    }
}
