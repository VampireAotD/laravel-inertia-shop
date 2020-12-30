<?php

namespace App\Services\Frontend\Favorite;

use App\Models\Product;
use Carbon\Carbon;

class FavoriteService
{
    /**
     * Add product to favorite list
     *
     * @param Product $product
     * @return bool
     */
    public function addToFavoriteList(Product $product)
    {
        $favorite_list = \Cookie::get('favorite_list');

        if (!$favorite_list) {
            \Cookie::queue('favorite_list', json_encode([$product->id]), $this->defaultCookieLifeTime());
        } else {

            $favorite_list = json_decode($favorite_list);

            if (in_array($product->id, $favorite_list)) { // If product already in favorite list than don't add it again
                return false;
            }

            $favorite_list[] = $product->id;

            \Cookie::queue('favorite_list', json_encode($favorite_list), $this->defaultCookieLifeTime());
        }

        return true;
    }

    /**
     * Remove product from favorite list
     *
     * @param Product $product
     */
    public function removeFromFavoriteList(Product $product): void
    {
        $favorite_list = \Cookie::get('favorite_list');

        $favorite_list = json_decode($favorite_list);

        if ($favorite_list && in_array($product->id, $favorite_list)) {
            $product_index = array_search($product->id, $favorite_list);

            unset($favorite_list[$product_index]);

            $favorite_list = array_values($favorite_list);
        }

        if (count($favorite_list) <= 0) {
            $this->deleteFavoriteList();
            return;
        }

        \Cookie::queue('favorite_list', json_encode($favorite_list), $this->defaultCookieLifeTime());
    }

    /**
     * Delete favorite list
     */
    public function deleteFavoriteList(): void
    {
        \Cookie::queue(\Cookie::forget('favorite_list'));
    }

    /**
     * Return default date for cookie life time
     *
     * @return int
     */
    private function defaultCookieLifeTime(): int
    {
        return Carbon::now()->addMonths(6)->diffInMinutes();
    }
}
