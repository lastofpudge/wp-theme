<?php

use App\Controllers\CategoryController;
use App\Controllers\PageController;
use App\Controllers\PostController;
use Core\Route;

if (is_home() || is_archive() || is_search()) {
    if (is_author()) {
        wp_safe_redirect(home_url(), 301);
        exit;
    }

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
