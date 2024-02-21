<?php

use App\Controllers\ProductController;
use Core\Route;

Route::load(ProductController::class, 'category', 'woocommerce/product');
