<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\OrderItem;
use App\Models\Review;
use App\Models\User;

interface ReviewServiceContract
{
    public function canReview(User $user, OrderItem $orderItem): bool;

    /**
     * @param  array{rating:int,content?:string|null}  $attributes
     */
    public function createForOrderItem(User $user, OrderItem $orderItem, array $attributes): Review;

    public function hasExistingReview(OrderItem $orderItem): bool;
}
