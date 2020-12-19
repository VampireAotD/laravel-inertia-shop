<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $order = [];

        for ($i = 0; $i < rand(1, 10); $i++) {
            $order[] = Product::pluck('id')->random();
        }

        return [
            'user_id' => User::pluck('id')->random(),
            'order' => json_encode($order),
            'status' => rand(0, 1),
        ];
    }
}
