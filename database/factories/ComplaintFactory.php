<?php

namespace Database\Factories;

use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Complaint>
 */
class ComplaintFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_item_id' => OrderItem::factory(),
            'description' => fake()->paragraph(),
            'response' => null,
            'status' => ComplaintStatus::Pending,
            'resolved_at' => null,
        ];
    }
}
