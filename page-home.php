<?php
/*
 * Template Name: Главная
 */

require_once __DIR__.'/app/Controllers/pageController.php';

Timber::render(['/pages/home.twig'], $d->index());
