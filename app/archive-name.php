<?php

require_once __DIR__.'/Controllers/categoryController.php';

Timber::render(['/categories/category.twig'], $d->index());
