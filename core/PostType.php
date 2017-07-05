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
}
