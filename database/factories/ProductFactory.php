<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'seller_id' => User::factory(),
            'category_id' => Category::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->optional()->paragraph(),
            'price' => fake()->numberBetween(500000, 30000000),
            'stock' => fake()->numberBetween(1, 10),
            'average_rating' => 0,
            'reviews_count' => 0,
            'status' => ProductStatus::Approved,
        ];
    }
}
