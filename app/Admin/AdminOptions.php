<?php

namespace App\Admin;

class AdminOptions
{
    public function __construct()
    {
        self::index();
        add_action('after_setup_theme', array( $this, 'registerMenus' ));
        add_filter('timber_context', array( $this, 'add_to_context' ));
    }

    /*
     * twicks
     */
    public static function index()
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
    }

    /*
     * register menus
     */
    public static function registerMenus()
    {
        register_nav_menus(array(
            'header_menu' => 'Header Menu',
            // 'footer_menu' => 'Footer menu'
        ));
    }

    /*
     * add menus to timber
     */
    public static function add_to_context($context)
    {
        $context['header_menu'] = new TimberMenu();
        return $context;
    }
}
