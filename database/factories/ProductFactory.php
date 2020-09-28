<?php

namespace Database\Factories;

use App\Models\Product;
use Database\Factories\traits\FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $name = $this->faker->name . ' product',
            'slug' => Str::slug($name),
            'price' => $this->faker->numberBetween(500, 1000),
            'amount' => $this->faker->numberBetween(0, rand(100, 5000)),
            'description' => rand(0, 1) ? $this->faker->realText(rand(100, 5000)) : null,
            'views' => $this->faker->numberBetween(0, 1000)
        ];
    }
}
