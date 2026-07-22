<?php

namespace Tests\Feature\Foundation;

use Tests\TestCase;

class LegacyIsolationTest extends TestCase
{
    public function test_legacy_code_is_not_autoloaded_as_active_app_code(): void
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('app/', $composer['autoload']['psr-4']['App\\']);
        $this->assertArrayNotHasKey('legacy\\', $composer['autoload']['psr-4']);
        $this->assertFalse(class_exists('App\\Models\\NguoiDung'));
    }
}
