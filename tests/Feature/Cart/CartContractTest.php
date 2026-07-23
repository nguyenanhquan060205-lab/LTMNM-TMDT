<?php

namespace Tests\Feature\Cart;

use App\Contracts\Services\CartServiceContract;
use Tests\TestCase;

class CartContractTest extends TestCase
{
    public function test_cart_routes_are_named_protected_and_mutations_are_not_get(): void
    {
        foreach (['cart.items.store', 'cart.items.update', 'cart.items.destroy'] as $name) {
            $route = app('router')->getRoutes()->getByName($name);

            $this->assertContains('auth', $route->gatherMiddleware());
            $this->assertNotContains('GET', $route->methods());
        }

        $this->assertTrue(interface_exists(CartServiceContract::class));
        $this->assertTrue(method_exists(CartServiceContract::class, 'getOrCreateForUser'));
        $this->assertTrue(method_exists(CartServiceContract::class, 'addProduct'));
    }
}
