<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'order_item_id' => OrderItem::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'content' => fake()->optional()->sentence(),
        ];
    }
}
