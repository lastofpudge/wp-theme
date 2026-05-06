<?php

/**
 * @see     https://woocommerce.com/document/template-structure/
 *
 * @version 8.6.0
 */

use App\Controllers\ProductController;
use Core\Route;

Route::load(ProductController::class, 'archive', 'woocommerce/shop');
