<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('post_meta', 'Fields for Homepage' )
    ->where('post_type', '=', 'page')
    ->where('post_template', '=', 'views/template-home.php')
    ->add_fields([
        Field::make('text', 'sub_title', 'Subtitle'),
        Field::make('text', 'description', 'Description'),
        Field::make('rich_text', 'textarea', 'Rich text'),
    ])
    ->add_tab(__('Complex'), [
        Field::make('complex', 'tech_items', 'Technologies list')->add_fields([
            Field::make('text', 'tech_title', 'Card title'),
            Field::make('complex', 'tech_sub_items', 'Technologies sub-items')
                ->add_fields([Field::make('text', 'tech_title', 'Card title'), Field::make('image', 'tech_image', '')->set_value_type('url')])
                ->set_layout('tabbed-horizontal'),
        ]),
    ]);
