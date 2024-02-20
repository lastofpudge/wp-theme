<?php

use App\Controllers\ProductController;
use Core\Route;

Route::load(ProductController::class, 'index', 'posts/product');
