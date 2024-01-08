<?php

namespace Core;

/**
 * Custom post types register.
 */
class PostType
{
    /**
     * Register a custom post type.
     *
     * @param string $url
     * @param string $name
     * @param string $singular
     * @param bool $public
     * @param bool $hasArchive
     * @param string $menuIcon
     * @param array $supports
     */
    public static function register(string $url, string $name, string $singular, bool $public, bool $hasArchive, string $menuIcon, array $supports): void
    {
        register_post_type($url, [
            'labels' => [
                'name' => __($name),
                'singular_name' => __($singular),
            ],
            'public' => $public,
            'has_archive' => $hasArchive,
            'menu_icon' => $menuIcon,
            'supports' => $supports,
        ]);
    }

    /**
     * Register a custom taxonomy.
     *
     * @param string $postType
     * @param string $taxUrl
     * @param string $taxName
     */
    public static function loadTax(string $postType, string $taxUrl, string $taxName): void
    {
        register_taxonomy(
            $taxUrl,
            [$postType],
            [
                'labels' => [
                    'name' => __($taxName),
                    'singular_name' => __('Category'),
                    'search_items' => __('Search'),
                    'all_items' => __('All categories'),
                    'parent_item' => __('Parent category'),
                    'edit_item' => __('Edit'),
                    'update_item' => __('Update'),
                    'add_new_item' => __('Add'),
                    'new_item_name' => __('New'),
                    'menu_name' => __('Categories'),
                ],
                'hierarchical' => true,
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
                'publicly_queryable' => true,
                'show_admin_column' => true,
                'query_var' => true,
            ]
        );
    }
}
