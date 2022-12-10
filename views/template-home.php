<?php

/*
 * Template Name: Homepage
 */

use App\Controllers\PageController;

makeView(PageController::class, "index", "pages/home");
