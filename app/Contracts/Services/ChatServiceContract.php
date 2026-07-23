<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Collection;

interface ChatServiceContract
{
    /**
     * @return Collection<int, Message>
     */
    public function conversation(User $viewer, User $participant, ?int $productId = null): Collection;

    /**
     * @param  array{receiver_id:int,product_id?:int|null,content?:string|null,image_path?:string|null}  $attributes
     */
    public function send(User $sender, array $attributes): Message;

    public function markAsRead(User $viewer, Message $message): Message;
}
