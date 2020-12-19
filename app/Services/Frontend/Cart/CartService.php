<?php

namespace App\Services\Frontend\Cart;

use App\Events\UserOrdered;
use App\Models\Product;
use App\Models\User;

class CartService
{
    /**
     * Process user order
     *
     * @param User $user
     * @return bool
     */
    public function order(User $user)
    {
        if(session()->get('cart')){
            $order = $user->orders()->make([
                'order' => session()->get('cart'),
            ]);

            if($order->save()){
                event(new UserOrdered($order));

                session()->forget('cart');
                session('cart', []);

                return true;
            }

            return false;
        }

        return false;
    }

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
