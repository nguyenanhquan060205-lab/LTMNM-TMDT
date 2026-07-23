<?php

namespace Tests\Feature\Foundation;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MigrationSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_canonical_tables_exist(): void
    {
        foreach ([
            'users',
            'categories',
            'products',
            'product_images',
            'carts',
            'cart_items',
            'orders',
            'order_items',
            'reviews',
            'complaints',
            'messages',
            'notifications',
            'sessions',
            'cache',
            'jobs',
            'failed_jobs',
            'job_batches',
        ] as $table) {
            $this->assertTrue(Schema::hasTable($table), "Missing table {$table}");
        }
    }

    public function test_canonical_columns_exist(): void
    {
        $this->assertTrue(Schema::hasColumns('users', ['id', 'username', 'password', 'is_locked']));
        $this->assertTrue(Schema::hasColumns('products', ['seller_id', 'category_id', 'status', 'deleted_at']));
        $this->assertTrue(Schema::hasColumns('order_items', ['product_name', 'unit_price', 'subtotal']));
        $this->assertTrue(Schema::hasColumns('notifications', ['id', 'type', 'notifiable_type', 'notifiable_id', 'data', 'read_at']));
    }
}
