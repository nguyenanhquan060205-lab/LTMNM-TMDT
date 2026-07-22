<?php

use App\Enums\ComplaintStatus;
use App\Enums\OrderItemStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100)->unique();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->decimal('price', 18, 2)->unsigned();
            $table->unsignedInteger('stock');
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->string('status', 20)->default(ProductStatus::Approved->value);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['seller_id', 'category_id', 'status', 'created_at']);
            $table->index('name');
        });

        Schema::create('product_images', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->boolean('is_cover')->default(false);
            $table->timestamps();

            $table->index(['product_id', 'is_cover']);
        });

        Schema::create('carts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->timestamps();

            $table->unique(['cart_id', 'product_id']);
        });

        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('seller_id')->constrained('users')->restrictOnDelete();
            $table->decimal('total_amount', 18, 2);
            $table->string('payment_method', 30);
            $table->string('payment_status', 20)->default(PaymentStatus::Unpaid->value);
            $table->string('shipping_address');
            $table->string('status', 20)->default(OrderStatus::Pending->value);
            $table->timestamp('ordered_at');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['buyer_id', 'seller_id', 'status', 'ordered_at']);
        });

        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->string('product_name', 200);
            $table->decimal('unit_price', 18, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('subtotal', 18, 2);
            $table->string('status', 20)->default(OrderItemStatus::Pending->value);
            $table->timestamps();

            $table->unique(['order_id', 'product_id']);
            $table->index('status');
        });

        Schema::create('reviews', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('order_item_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('content')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'user_id']);
        });

        Schema::create('complaints', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('order_item_id')->constrained()->restrictOnDelete();
            $table->text('description');
            $table->text('response')->nullable();
            $table->string('status', 20)->default(ComplaintStatus::Pending->value);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'order_item_id', 'status']);
        });

        Schema::create('messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('receiver_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->text('content')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['sender_id', 'receiver_id', 'created_at']);
            $table->index(['receiver_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
