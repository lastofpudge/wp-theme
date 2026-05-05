<?php

use App\Controllers\AccountController;
use App\Controllers\CategoryController;
use App\Controllers\PageController;
use App\Controllers\PostController;
use App\Controllers\ProductController;
use Core\Route;

if (function_exists('is_product_category') && is_product_category()) {
    Route::load(ProductController::class, 'archive', 'woocommerce/shop');
}

if (function_exists('is_product_tag') && is_product_tag()) {
    Route::load(ProductController::class, 'archive', 'woocommerce/shop');
}

if (function_exists('is_cart') && is_cart()) {
    Route::load(ProductController::class, 'cart', 'woocommerce/cart');
}

if (function_exists('is_checkout') && is_checkout()) {
    Route::load(AccountController::class, 'checkout', 'woocommerce/checkout');
}

if (function_exists('is_account_page') && is_account_page()) {
    Route::load(AccountController::class, 'account', 'woocommerce/account');
}

if (is_category() || is_tag() || is_search()) {
    Route::load(CategoryController::class, 'index', 'categories/category');
}

if (is_front_page()) {
    Route::load(PageController::class, 'index', 'index');
}

if (is_single()) {
    Route::load(PostController::class, 'index', 'posts/post');
}

if (is_page()) {
    Route::load(PageController::class, 'index', 'pages/page');
}

if (is_404()) {
    Route::view('pages/404');
}

if (is_author()) {
    wp_redirect(home_url(), 301);
    exit;
}
