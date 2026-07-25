<?php

namespace Tests\Feature\Foundation;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\Message;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_core_relationships_resolve(): void
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->for($seller, 'seller')->for($category)->create();
        $cart = Cart::factory()->for($buyer)->create();
        $cartItem = CartItem::factory()->for($cart)->for($product)->create();
        $order = Order::factory()->for($buyer, 'buyer')->for($seller, 'seller')->create();
        $orderItem = OrderItem::factory()->for($order)->for($product)->create(['product_name' => $product->name]);
        $review = Review::factory()->for($buyer, 'user')->for($product)->for($orderItem)->create();
        $complaint = Complaint::factory()->for($buyer, 'user')->for($orderItem)->create();
        $message = Message::factory()->for($buyer, 'sender')->for($seller, 'receiver')->for($product)->create();

        $this->assertTrue($buyer->cart->is($cart));
        $this->assertTrue($cartItem->product->is($product));
        $this->assertTrue($order->buyer->is($buyer));
        $this->assertTrue($order->seller->is($seller));
        $this->assertTrue($orderItem->review->is($review));
        $this->assertTrue($complaint->orderItem->is($orderItem));
        $this->assertTrue($message->sender->is($buyer));
        $this->assertTrue($message->receiver->is($seller));
    }
}
