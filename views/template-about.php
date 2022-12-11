<?php

/*
 * Template Name: About page
 */

use App\Controllers\PageController;
use Core\Route;

Route::load(PageController::class, 'about', 'pages/pages');
