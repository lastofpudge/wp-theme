<?php

/*
 * Template Name: News/Lists
 */

use App\Controllers\PageController;
use Core\Route;

Route::load(PageController::class, 'list', 'pages/list');
