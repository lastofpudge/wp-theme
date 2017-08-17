<?php

require_once(__DIR__ . '/app/Controllers/categoryController.php');

Timber::render(array('/categories/category.twig'), $d->index());
