<?php

use Diglactic\Breadcrumbs\Breadcrumbs;

// Frontend

// Home

Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

// Home > Product

Breadcrumbs::for('product', function ($trail, $product) {
    $trail->parent('home');
    $trail->push($product->name, route('product', $product->slug));
});

// Home > Cart

Breadcrumbs::for('cart', function ($trail) {
    $trail->parent('home');
    $trail->push('Cart', route('cart'));
});

// Home > Favorite list

Breadcrumbs::for('favorite', function ($trail) {
    $trail->parent('home');
    $trail->push('Favorite list', route('favorite-list'));
});
