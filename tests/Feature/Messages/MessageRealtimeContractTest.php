<?php

namespace Tests\Feature\Messages;

use App\Contracts\Services\ChatServiceContract;
use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Broadcast;
use Tests\TestCase;

class MessageRealtimeContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_private_channel_is_loaded_and_authorized_only_for_owner(): void
    {
        $channels = Broadcast::getChannels();

        $this->assertTrue($channels->has('users.{userId}'));

        $callback = $channels->get('users.{userId}');
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $this->assertTrue($callback($owner, (string) $owner->id));
        $this->assertFalse($callback($other, (string) $owner->id));
    }

    public function test_message_sent_event_alias_channels_and_payload_are_public_contract(): void
    {
        $message = Message::factory()->create([
            'content' => 'Hello',
            'image_path' => 'messages/example.jpg',
        ]);

        $event = new MessageSent($message);
        $channels = $event->broadcastOn();
        $payload = $event->broadcastWith();

        $this->assertSame('message.sent', $event->broadcastAs());
        $this->assertContainsOnlyInstancesOf(PrivateChannel::class, $channels);
        $this->assertSame('private-users.'.$message->receiver_id, (string) $channels[0]);
        $this->assertSame('private-users.'.$message->sender_id, (string) $channels[1]);
        $this->assertSame($message->id, $payload['message_id']);
        $this->assertSame($message->sender_id, $payload['sender_id']);
        $this->assertSame($message->receiver_id, $payload['receiver_id']);
        $this->assertSame($message->product_id, $payload['product_id']);
        $this->assertSame('Hello', $payload['content']);
        $this->assertSame('messages/example.jpg', $payload['image_path']);
        $this->assertArrayNotHasKey('password', $payload);
        $this->assertArrayNotHasKey('email', $payload);
        $this->assertArrayNotHasKey('bank_account_number', $payload);
        $this->assertTrue(interface_exists(ChatServiceContract::class));
    }
}
