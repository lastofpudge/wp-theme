<?php

/*
 * Template Name: Checkout page
 */

use App\Controllers\CheckoutController;
use Core\Route;

Route::load(CheckoutController::class, 'checkout', 'woocommerce/checkout');
