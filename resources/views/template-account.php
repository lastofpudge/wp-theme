<?php

/*
 * Template Name: Account page
 */

use App\Controllers\PageController;
use Core\Route;

Route::load(PageController::class, 'account', 'woocommerce/account');
