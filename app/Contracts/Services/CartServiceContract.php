<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

interface CartServiceContract
{
    public function getOrCreateForUser(User $user): Cart;

    public function addProduct(User $user, Product $product, int $quantity): CartItem;

    public function updateQuantity(CartItem $cartItem, int $quantity): CartItem;

    public function removeItem(CartItem $cartItem): bool;
}
