<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Product $product): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return ! $user->is_locked;
    }

    public function update(User $user, Product $product): bool
    {
        return $user->id === $product->seller_id || $user->role === UserRole::Admin;
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->id === $product->seller_id || $user->role === UserRole::Admin;
    }
}
