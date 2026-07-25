<?php

namespace Tests\Unit\Foundation;

use PHPUnit\Framework\TestCase;

class DatabaseSafetyTest extends TestCase
{
    public function test_phpunit_uses_safe_database_configuration(): void
    {
        $phpunit = file_get_contents(__DIR__.'/../../../phpunit.xml');

        $this->assertStringContainsString('name="DB_CONNECTION" value="mysql"', $phpunit);
        $this->assertStringContainsString('name="DB_DATABASE" value="techsecond_test"', $phpunit);
        $this->assertStringNotContainsString('name="DB_DATABASE" value="techsecond"', $phpunit);
        $this->assertStringNotContainsString('TMDT', $phpunit);
        $this->assertStringNotContainsString('production', $phpunit);
        $this->assertStringNotContainsString('prod', $phpunit);
        $this->assertStringNotContainsString('staging', $phpunit);
    }
}
