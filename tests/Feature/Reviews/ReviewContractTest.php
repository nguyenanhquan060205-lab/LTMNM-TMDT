<?php

namespace Tests\Feature\Reviews;

use App\Contracts\Services\ReviewServiceContract;
use App\Models\OrderItem;
use ReflectionMethod;
use Tests\TestCase;

class ReviewContractTest extends TestCase
{
    public function test_review_route_and_order_item_contract_are_stable(): void
    {
        $route = app('router')->getRoutes()->getByName('reviews.store');

        $this->assertContains('auth', $route->gatherMiddleware());
        $this->assertTrue(interface_exists(ReviewServiceContract::class));

        $method = new ReflectionMethod(ReviewServiceContract::class, 'createForOrderItem');

        $this->assertSame(OrderItem::class, $method->getParameters()[1]->getType()?->getName());
    }
}
