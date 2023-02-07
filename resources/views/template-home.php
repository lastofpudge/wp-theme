<?php

/*
 * Template Name: Homepage
 */

use App\Controllers\PageController;
use Core\Route;

Route::load(PageController::class, 'index', 'pages/home');
