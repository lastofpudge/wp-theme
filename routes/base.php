<?php

use App\Controllers\CategoryController;
use App\Controllers\PageController;
use App\Controllers\PostController;

use Core\Route;

if (is_category() || is_tag() || is_search()) {
    Route::load(CategoryController::class, 'index', 'categories/category');
}

if (is_front_page()) {
    Route::load(PageController::class, 'index', 'index');
}

if (is_single()) {
    Route::load(PostController::class, 'index', 'posts/post');
}

if (is_page()) {
    Route::load(PageController::class, 'index', 'pages/page');
}

if (is_404()) {
    Route::view('pages/404');
}
