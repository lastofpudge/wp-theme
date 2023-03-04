<?php

use Carbon_Fields\Block;
use Carbon_Fields\Field;

Block::make(__('My Gutenberg Block'))
    ->add_fields([
        Field::make('text', 'heading', __('Block Heading')),
        Field::make('image', 'image', __('Block Image')),
        Field::make('rich_text', 'content', __('Block Content')),
    ])
    ->set_description(__('Description for the block'))
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) {
        include_once(__DIR__ . '/example.view.php');
    });
