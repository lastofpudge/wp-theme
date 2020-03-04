<?php

/**
 * Custom post types register.
 */
class PostType
{
    public static function register($url, $name, $singular, $public, $has_archive, $menu_icon, $supports)
    {
        register_post_type($url,
            [
                'labels' => [
                    'name'          => __($name),
                    'singular_name' => __($singular),
                ],
                'public'      => $public,
                'has_archive' => $has_archive,
                'menu_icon'   => $menu_icon,
                'supports'    => $supports,
            ]
        );
    }

    public static function loadtax($post_type, $tax_url, $tax_name)
    {
        $labels = [
            'name'              => _x($tax_name, 'taxonomy general name'),
            'singular_name'     => _x('Категорий', 'taxonomy singular name'),
            'search_items'      => __('Поиск'),
            'all_items'         => __('Все категории'),
            'parent_item'       => __('Родительская категория'),
            'parent_item_colon' => __('Родительская категория:'),
            'edit_item'         => __('Edit'),
            'update_item'       => __('Update'),
            'add_new_item'      => __('Add'),
            'new_item_name'     => __('New'),
            'menu_name'         => __('Категории'),
        ];

        register_taxonomy($tax_url, [$post_type], [
            'hierarchical'       => true,
            'public'             => true,
            'labels'             => $labels,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_nav_menus'  => true,
            'publicly_queryable' => true,
            'show_admin_column'  => true,
            'query_var'          => true,
        ]);
    }
}
