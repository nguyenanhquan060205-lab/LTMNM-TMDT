<?php

namespace Tests\Feature\Foundation;

use Tests\TestCase;

class StorageConfigurationTest extends TestCase
{
    public function test_public_storage_disk_is_configured(): void
    {
        $this->assertSame('public', config('filesystems.default'));
        $this->assertSame('local', config('filesystems.disks.public.driver'));
        $this->assertStringEndsWith('storage/app/public', str_replace('\\', '/', config('filesystems.disks.public.root')));
    }
}
