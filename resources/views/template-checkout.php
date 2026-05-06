<?php

/*
 * Template Name: Checkout page
 */

use App\Controllers\ProductController;
use Core\Route;

Route::load(ProductController::class, 'checkout', 'woocommerce/checkout');
