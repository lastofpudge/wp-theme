<?php

require_once __DIR__.'/app/Controllers/categoryController.php';

Timber::render(['/categories/category.twig'], $d->index());
