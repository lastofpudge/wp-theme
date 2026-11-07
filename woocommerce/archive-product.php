<?php

/**
 * @see     https://woocommerce.com/document/template-structure/
 *
 * @version 8.6.0
 */

use App\Controllers\ShopController;
use Core\Route;

Route::load(ShopController::class, 'archive', 'woocommerce/shop');
