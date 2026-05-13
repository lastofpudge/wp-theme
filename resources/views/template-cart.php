<?php

/*
 * Template Name: Cart page
 */

use App\Controllers\CheckoutController;
use Core\Route;

Route::load(CheckoutController::class, 'cart', 'woocommerce/cart');
