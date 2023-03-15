<?php

use Carbon_Fields\Block;
use Carbon_Fields\Field;

wp_register_style(
    'crb-my-shiny-gutenberg-block-stylesheet',
    get_stylesheet_directory_uri() . '../app/CarbonFields/Blocks/example.css'
);


Block::make(__('My Gutenberg Block'))
    ->add_fields([
        Field::make('text', 'heading', __('Block Heading')),
        Field::make('image', 'image', __('Block Image')),
        Field::make('rich_text', 'content', __('Block Content')),
    ])
    ->set_description(__('Description for the block'))
    ->set_editor_style('crb-my-shiny-gutenberg-block-stylesheet')
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) {
        include(__DIR__ . '/example.view.php');
    });
