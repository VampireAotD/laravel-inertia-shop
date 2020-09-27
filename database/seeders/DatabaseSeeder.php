<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         User::factory(10)->create();
         Category::factory(15)->create();
         Product::factory(100)->create();
         Slide::factory(8)->create();
         ProductCategory::factory(250)->create();
         Order::factory(150)->create();
    }
}
