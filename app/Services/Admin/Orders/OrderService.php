<?php

namespace App\Services\Admin\Orders;

use App\DTO\RabbitMq\UserEmailMessage;
use App\Models\Order;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Accept the user order
     *
     * @param Order $order
     * @return bool
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function accept(Order $order)
    {
        if ($order->update(['status' => 1])) {
            $products = $order->ordered_products->pluck('name');

            $products->transform(function ($product) {
                return [
                    'product' => $product,
                    'key' => rtrim(chunk_split(Str::random(20), 5, '-'), '-')
                ];
            });

            rabbitmq()->sendMessage(
                new UserEmailMessage($order->user->email, $products->toArray()),
                'email',
                'users'
            );

            return true;
        }

        return false;
    }
}
