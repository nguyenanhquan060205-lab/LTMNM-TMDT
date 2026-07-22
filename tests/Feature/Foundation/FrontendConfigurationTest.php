<?php

namespace Tests\Feature\Foundation;

use Tests\TestCase;

class FrontendConfigurationTest extends TestCase
{
    public function test_bootstrap_vite_frontend_contract(): void
    {
        $package = json_decode(file_get_contents(base_path('package.json')), true, flags: JSON_THROW_ON_ERROR);
        $vite = file_get_contents(base_path('vite.config.js'));

        $this->assertArrayHasKey('bootstrap', $package['dependencies']);
        $this->assertArrayHasKey('@popperjs/core', $package['dependencies']);
        $this->assertArrayNotHasKey('tailwindcss', $package['devDependencies'] ?? []);
        $this->assertStringContainsString('resources/css/app.css', $vite);
        $this->assertStringContainsString('resources/js/app.js', $vite);
    }
}
