<?php

/*
 * Template Name: Account page
 */

use App\Controllers\AccountController;
use Core\Route;

Route::load(AccountController::class, 'account', 'woocommerce/account');
