<?php

/*
 * register custom post types
 */
add_action('init', 'create_post_types');

/*
 * articles
 */
function create_post_types()
{
    register_post_type('test',
        array(
          'labels' => array(
            'name' => __('Test'),
            'singular_name' => __('test')
          ),
          'public' => true,
          'has_archive' => true,
          'menu_icon'   => 'dashicons-format-aside',
          'supports' => array( 'title', 'editor')
        )
    );
}
