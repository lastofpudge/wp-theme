<?php

use App\Controllers\AccountController;
use App\Controllers\CategoryController;
use App\Controllers\CheckoutController;
use App\Controllers\PageController;
use App\Controllers\PostController;
use App\Controllers\ShopController;
use Core\Route;

if (function_exists('is_shop') && is_shop()) {
    Route::load(ShopController::class, 'archive', 'woocommerce/shop');
}

if (function_exists('is_product_category') && is_product_category()) {
    Route::load(ShopController::class, 'archive', 'woocommerce/shop');
}

if (function_exists('is_product_tag') && is_product_tag()) {
    Route::load(ShopController::class, 'archive', 'woocommerce/shop');
}

if (function_exists('is_tax') && is_tax('product_brand')) {
    Route::load(ShopController::class, 'archive', 'woocommerce/shop');
}

if (function_exists('wc_get_attribute_taxonomy_names') && function_exists('is_tax') && is_tax(wc_get_attribute_taxonomy_names())) {
    Route::load(ShopController::class, 'archive', 'woocommerce/shop');
}

if (function_exists('is_cart') && is_cart()) {
    Route::load(CheckoutController::class, 'cart', 'woocommerce/cart');
}

if (function_exists('is_checkout') && is_checkout()) {
    Route::load(CheckoutController::class, 'checkout', 'woocommerce/checkout');
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

if (is_page() && !is_cart() && !is_checkout() && !is_account_page()) {
    Route::load(PageController::class, 'page', 'pages/page');
}

if (is_404()) {
    Route::view('pages/404');
}

if (is_author()) {
    wp_redirect(home_url(), 301);
    exit;
}
