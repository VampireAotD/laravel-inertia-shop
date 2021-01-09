<?php

namespace App\Events;

use App\DTO\RabbitMq\AdminEmailMessageDto;
use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserOrdered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var AdminEmailMessageDto
     */
    public $email;

    /**
     * Create a new event instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $email = new AdminEmailMessageDto(
            config('admin.email'),
            $order->user->name,
            json_decode($order->order)
        );

        $this->email = $email;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
