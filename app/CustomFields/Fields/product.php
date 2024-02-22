<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

// not works with woo new form
Container::make('post_meta', __('Products Data'))
    ->where('post_type', '=', 'product')
    ->add_fields([
        Field::make('text', 'sub_title', 'Sub-title'),
    ]);
