<?php

/*
 * Template Name: Cart page
 */

use App\Controllers\ProductController;
use Core\Route;

Route::load(ProductController::class, 'checkout', 'woocommerce/cart');
