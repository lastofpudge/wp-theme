<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('post_meta', 'Fields for About page')
    ->where('post_type', '=', 'page')
    ->where('post_template', '=', 'views/template-about.php')
    ->add_fields([Field::make('rich_text', 'new_short_text', 'About description')]);
