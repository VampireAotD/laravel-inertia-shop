<?php

namespace App\Listeners\Orders;

use App\Events\UserOrdered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewOrderNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UserOrdered $event
     * @return void
     */
    public function handle(UserOrdered $event)
    {
        rabbitmq()->sendMessage($event->email, 'email', 'admins');
    }
}
