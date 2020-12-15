<?php

namespace App\Services\Frontend\Products;

use App\Models\Product;
use Illuminate\Support\Facades\Redis;

class ProductService
{
    /**
     * Addition actions to one Product entity
     *
     * @param Product $product
     * @param string $ip
     * @return mixed
     */
    public function additionalActions(Product $product, string $ip)
    {
        $product->increment('views');

        if (Redis::hExists('users', "user:$ip")) {
            $productList = json_decode(Redis::hGet('users', "user:$ip"));

            foreach ($productList as $key => $productItem) {
                if ($product->id === $productItem->id) {
                    unset($productList[$key]);
                }
            }

            $productList[] = [
                'id' => $product->id,
                'expire' => now()->addDays(3)->timestamp
            ];

            return Redis::hSet('users', "user:$ip", json_encode(array_values($productList)));
        }

        return Redis::hSet('users', "user:$ip", json_encode([['id' => $product->id, 'expire' => now()->addDays(3)->timestamp]]));
    }
}
