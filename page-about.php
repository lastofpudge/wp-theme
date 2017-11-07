<?php
/*
 * Template Name: О нас
 */

require_once(__DIR__ . '/app/Controllers/pageController.php');

Timber::render(array('/pages/about.twig'), $d->index());
