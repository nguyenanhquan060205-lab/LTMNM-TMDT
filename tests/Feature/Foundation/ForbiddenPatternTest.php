<?php

namespace Tests\Feature\Foundation;

use Tests\TestCase;

class ForbiddenPatternTest extends TestCase
{
    public function test_forbidden_pattern_scanner_passes(): void
    {
        exec(PHP_BINARY.' '.escapeshellarg(base_path('tools/quality/check-forbidden-patterns.php')), $output, $code);

        $this->assertSame(0, $code, implode(PHP_EOL, $output));
    }
}
