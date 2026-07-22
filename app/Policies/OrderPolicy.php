<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->buyer_id
            || $user->id === $order->seller_id
            || $user->role === UserRole::Admin;
    }

    public function cancel(User $user, Order $order): bool
    {
        return $user->id === $order->buyer_id;
    }

    public function process(User $user, Order $order): bool
    {
        return $user->id === $order->seller_id;
    }
}
