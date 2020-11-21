<?php

namespace App\Services\Frontend\Cart;

use App\Models\Product;

class CartService
{
    /**
     * Add new item to cart
     *
     * @param Product $product
     * @return bool
     */
    public function addToCart(Product $product)
    {
        $cart = session()->get('cart');

        if (!$cart) {
            session(['cart' => json_encode([$product->id])]);
        } else {
            $cart = json_decode($cart);

            if (in_array($product->id, $cart)) { // If product already in cart than don't add it again
                return false;
            }

            $cart[] = $product->id;

            session(['cart' => json_encode($cart)]);
        }

        return true;
    }

    /**
     * Remove item from cart
     *
     * @param Product $product
     */
    public function removeFromCart(Product $product): void
    {
        $cart = session()->get('cart');

        $cart = json_decode($cart);

        if ($cart && in_array($product->id, $cart)) {
            $product_index = array_search($product->id, $cart);

            unset($cart[$product_index]);

            $cart = array_values($cart);
        }

        if (count($cart) <= 0) {
            $this->deleteCart();
            return;
        }

        session(['cart' => json_encode($cart)]);
    }

    /**
     * Clear cart
     */
    public function deleteCart()
    {
        return session()->forget('cart');
    }
}
