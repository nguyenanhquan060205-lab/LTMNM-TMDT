<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Collection;

interface SellerOrderServiceContract
{
    /**
     * @return Collection<int, Order>
     */
    public function listForSeller(User $seller): Collection;

    public function confirmItem(User $seller, OrderItem $orderItem): OrderItem;

    public function cancelItem(User $seller, OrderItem $orderItem): OrderItem;

    public function updateAggregateStatus(Order $order): Order;
}
