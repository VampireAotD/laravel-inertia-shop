<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Slide;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SlideFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Slide::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::pluck('id')->random(),
            'uuid' => Str::uuid(),
        ];
    }
}
