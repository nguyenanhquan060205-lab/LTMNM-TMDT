<?php

namespace Tests\Feature\Foundation;

use Tests\TestCase;

class ApplicationBootTest extends TestCase
{
    public function test_application_boots_home_route(): void
    {
        $this->get(route('home'))->assertOk();
    }
}
