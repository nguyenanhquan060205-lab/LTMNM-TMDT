<?php

namespace Database\Seeders;

use App\Enums\OrderItemStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Enums\UserRole;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = collect([
            ['username' => 'admin', 'full_name' => 'Admin System', 'role' => UserRole::Admin],
            ['username' => 'minhhuy', 'full_name' => 'Minh Huy', 'role' => UserRole::User],
            ['username' => 'hoapham', 'full_name' => 'Hoa Pham', 'role' => UserRole::User],
            ['username' => 'quocbao', 'full_name' => 'Quoc Bao', 'role' => UserRole::User],
        ])->mapWithKeys(function (array $data): array {
            $user = User::query()->updateOrCreate(
                ['username' => $data['username']],
                [
                    'full_name' => $data['full_name'],
                    'role' => $data['role'],
                    'email' => "{$data['username']}@example.test",
                    'phone' => null,
                    'address' => 'Local demo address',
                    'avatar_path' => 'avatars/default.jpg',
                    'is_locked' => false,
                    'password' => Hash::make('Password123!'),
                ]
            );

            Cart::query()->firstOrCreate(['user_id' => $user->id]);

            return [$data['username'] => $user];
        });

        $categories = collect([
            'Smartphones',
            'Laptops',
            'Tablets',
            'Accessories',
            'Smartwatches',
            'Audio',
        ])->mapWithKeys(fn (string $name): array => [
            $name => Category::query()->firstOrCreate(['name' => $name]),
        ]);

        $products = [
            ['seller' => 'minhhuy', 'category' => 'Smartphones', 'name' => 'iPhone 13 Pro Max 256GB', 'price' => 15000000],
            ['seller' => 'hoapham', 'category' => 'Laptops', 'name' => 'MacBook Pro M1 2020', 'price' => 22000000],
            ['seller' => 'minhhuy', 'category' => 'Audio', 'name' => 'AirPods Pro Used', 'price' => 1500000],
            ['seller' => 'quocbao', 'category' => 'Smartwatches', 'name' => 'Apple Watch Series 7', 'price' => 7500000],
            ['seller' => 'hoapham', 'category' => 'Smartphones', 'name' => 'Samsung Galaxy S22 Ultra', 'price' => 14000000],
            ['seller' => 'quocbao', 'category' => 'Accessories', 'name' => 'Mechanical Keyboard Kit', 'price' => 1800000],
        ];

        foreach ($products as $index => $data) {
            $product = Product::query()->updateOrCreate(
                ['name' => $data['name']],
                [
                    'seller_id' => $users[$data['seller']]->id,
                    'category_id' => $categories[$data['category']]->id,
                    'description' => 'Local foundation demo product.',
                    'price' => $data['price'],
                    'stock' => 2 + $index,
                    'average_rating' => 0,
                    'reviews_count' => 0,
                    'status' => ProductStatus::Approved,
                ]
            );

            ProductImage::query()->updateOrCreate(
                ['product_id' => $product->id, 'is_cover' => true],
                ['path' => 'products/default.jpg']
            );
        }

        $buyer = $users['minhhuy'];
        $seller = $users['hoapham'];
        $product = Product::query()->where('seller_id', $seller->id)->firstOrFail();

        $order = Order::query()->updateOrCreate(
            ['buyer_id' => $buyer->id, 'seller_id' => $seller->id, 'ordered_at' => now()->startOfMinute()],
            [
                'total_amount' => $product->price,
                'payment_method' => PaymentMethod::CashOnDelivery,
                'payment_status' => PaymentStatus::Unpaid,
                'shipping_address' => 'Local demo address',
                'status' => OrderStatus::Pending,
                'paid_at' => null,
            ]
        );

        OrderItem::query()->updateOrCreate(
            ['order_id' => $order->id, 'product_id' => $product->id],
            [
                'product_name' => $product->name,
                'unit_price' => $product->price,
                'quantity' => 1,
                'subtotal' => $product->price,
                'status' => OrderItemStatus::Pending,
            ]
        );
    }
}
