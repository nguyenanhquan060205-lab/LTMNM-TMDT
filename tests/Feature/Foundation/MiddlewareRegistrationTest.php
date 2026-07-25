<?php

namespace Tests\Feature\Foundation;

use Tests\TestCase;

class MiddlewareRegistrationTest extends TestCase
{
    public function test_middleware_aliases_are_registered(): void
    {
        $route = app('router')->getRoutes()->getByName('admin.dashboard');

        $this->assertNotNull($route);
        $this->assertContains('admin', $route->gatherMiddleware());
        $this->assertContains('not_locked', $route->gatherMiddleware());
    }
}
