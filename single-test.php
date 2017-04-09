<?php
/*
 * Template Name: Test
 */

require_once(__DIR__ . '/app/Controllers/postController.php');
Timber::render(array('/posts/post-test.twig'), $d->index());
