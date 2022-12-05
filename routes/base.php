<?php

use App\Controllers\CategoryController;
use App\Controllers\ErrorController;
use App\Controllers\PageController;
use App\Controllers\PostController;

if (is_search()) {
    makeView(CategoryController::class, 'index', 'categories/category');
}

if (is_category()) {
    makeView(CategoryController::class, 'index', 'categories/category');
}

if (is_front_page()) {
    makeView(PageController::class, 'index', 'index');
}

if (is_single()) {
    makeView(PostController::class, 'index', 'posts/post');
}

if (is_page()) {
    makeView(PageController::class, 'index', 'pages/page');
}

if (is_404()) {
    makeView(ErrorController::class, 'index', 'pages/404');
}
