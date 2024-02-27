<?php

use App\Controllers\CategoryController;
use App\Controllers\PageController;
use App\Controllers\PostController;
use App\Controllers\ProductController;
use Core\Route;

if (is_category() || is_tag() || is_search()) {
    Route::load(CategoryController::class, 'index', 'categories/category');
}

if (is_front_page()) {
    Route::load(PageController::class, 'index', 'index');
}

if (is_cart()) {
    Route::load(ProductController::class, 'cart', 'woocommerce/cart');
}

if (is_single()) {
    Route::load(PostController::class, 'index', 'posts/post');
}

if (is_account_page()) {
    Route::load(PageController::class, 'index', 'woocommerce/account');
}

if (is_checkout()) {
    Route::load(PageController::class, 'index', 'woocommerce/checkout');
}

if (is_page()) {
    Route::load(PageController::class, 'index', 'pages/page');
}

if (is_shop()) {
    Route::load(ProductController::class, 'archive', 'woocommerce/archive');
}

if (is_404()) {
    Route::view('pages/404');
}
