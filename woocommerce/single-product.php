<?php

/**
 * @see     https://woocommerce.com/document/template-structure/
 *
 * @version 1.6.4
 */

use App\Controllers\ProductController;
use Core\Route;

Route::load(ProductController::class, 'index', 'woocommerce/product');
