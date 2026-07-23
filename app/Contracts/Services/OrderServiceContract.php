<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;

interface OrderServiceContract
{
    /**
     * Checkout must run inside a database transaction and preserve inventory consistency.
     *
     * @param  array{payment_method:string,shipping_address:string}  $attributes
     * @return Collection<int, Order>
     */
    public function checkout(User $buyer, array $attributes): Collection;

    /**
     * @return Collection<int, Order>
     */
    public function listForBuyer(User $buyer): Collection;

    public function cancelByBuyer(User $buyer, Order $order): Order;
}
