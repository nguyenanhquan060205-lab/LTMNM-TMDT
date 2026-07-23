<?php

namespace Tests\Feature\Orders;

use App\Contracts\Services\OrderServiceContract;
use App\Contracts\Services\SellerOrderServiceContract;
use Illuminate\Support\Collection;
use ReflectionMethod;
use Tests\TestCase;

class OrderContractTest extends TestCase
{
    public function test_order_routes_and_transaction_oriented_contracts_are_stable(): void
    {
        $checkoutRoute = app('router')->getRoutes()->getByName('orders.store');

        $this->assertContains('auth', $checkoutRoute->gatherMiddleware());
        $this->assertContains('not_locked', $checkoutRoute->gatherMiddleware());
        $this->assertTrue(interface_exists(OrderServiceContract::class));
        $this->assertTrue(interface_exists(SellerOrderServiceContract::class));

        $checkout = new ReflectionMethod(OrderServiceContract::class, 'checkout');

        $this->assertSame(Collection::class, $checkout->getReturnType()?->getName());
        $this->assertStringContainsString('transaction', (string) $checkout->getDocComment());
    }
}
