<?php
/*
 * Template Name: О нас
 */

require_once __DIR__.'/Controllers/pageController.php';

Timber::render(['/pages/about.twig'], $d->index());
